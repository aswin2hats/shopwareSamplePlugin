<?php declare(strict_types=1);

namespace SwagPackageCollection\Storefront\Controller;

use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\RateLimiter\Exception\RateLimitExceededException;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\Framework\Validation\Exception\ConstraintViolationException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\Framework\Routing\Annotation\Since;
use SwagPackageCollection\Core\Content\Package\Service\AbstractPackageCollectionFormRoute;
use Doctrine\DBAL\Connection;

/**
 * @Route(defaults={"_routeScope"={"storefront"}})
 *
 * @deprecated tag:v6.5.0 - reason:becomes-internal - Will be internal
 */
#[Package('content')]
class PackageFormController extends StorefrontController
{
    /**
     * @var AbstractPackageCollectionFormRoute
     */
    private $packageCollectionFormRoute;

    /**
     * @var Connection
     */
    private $connection;

    public function __construct(AbstractPackageCollectionFormRoute $packageCollectionFormRoute, Connection $connection)
    {
        $this->packageCollectionFormRoute = $packageCollectionFormRoute;
        $this->connection = $connection;
    }

    /**
     * @Since("6.1.0.0")
     * @Route("/form/package_collection", name="frontend.form.package_collection.send", methods={"POST"}, defaults={"XmlHttpRequest"=true, "_captcha"=true})
     */
    public function sendPackageCollectionForm(RequestDataBag $data, SalesChannelContext $context): JsonResponse
    {
        $response = [];

        $packageTypes = $data->get('package_type');
        $quantities = $data->get('quantity');
        $packageTypeData = [];
        foreach ($packageTypes as $index => $packageType) {
            $indexString = (string) $index;
            $packageArr[$packageType] =$quantities->get($indexString);
            $sql = 'SELECT * FROM swag_package WHERE name = :packageType';
            $packageTypeDetails = $this->connection->executeQuery($sql, ['packageType' => $packageType])->fetch();

            if ($packageTypeDetails) {
                 $packageTypeData[$packageType] = $packageTypeDetails;
             }

        }
        $data->set('packageTypeData',$packageTypeData);
        $data->set('packageArr',$packageArr);

        try {
            $message = $this->packageCollectionFormRoute
                ->load($data->toRequestDataBag(), $context)
                ->getResult()
                ->getIndividualSuccessMessage();

            if (!$message) {
                $message = $this->trans('contact.success');
            }
            $response[] = [
                'type' => 'success',
                'alert' => $message,
            ];
        } catch (ConstraintViolationException $formViolations) {
            dd($formViolations);
            $violations = [];
            foreach ($formViolations->getViolations() as $violation) {
                $violations[] = $violation->getMessage();
            }
            $response[] = [
                'type' => 'danger',
                'alert' => $this->renderView('@Storefront/storefront/utilities/alert.html.twig', [
                    'type' => 'danger',
                    'list' => $violations,
                ]),
            ];
        } catch (RateLimitExceededException $exception) {
            $response[] = [
                'type' => 'info',
                'alert' => $this->renderView('@Storefront/storefront/utilities/alert.html.twig', [
                    'type' => 'info',
                    'content' => $this->trans('error.rateLimitExceeded', ['%seconds%' => $exception->getWaitTime()]),
                ]),
            ];
        }

        return new JsonResponse($response);
    }
}