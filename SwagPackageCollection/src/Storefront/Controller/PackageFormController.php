<?php declare(strict_types=1);

namespace SwagPackageCollection\Storefront\Controller;

use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\RateLimiter\Exception\RateLimitExceededException;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\Framework\Validation\Exception\ConstraintViolationException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use SwagPackageCollection\Core\Content\Package\SalesChannel\AbstractPackageCollectionFormRoute;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\Framework\Routing\Annotation\Since;

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

    public function __construct(AbstractPackageCollectionFormRoute $packageCollectionFormRoute)
    {
        $this->packageCollectionFormRoute = $packageCollectionFormRoute;
    }

    /**
     * @Since("6.1.0.0")
     * @Route("/form/package_collection", name="frontend.form.package_collection.send", methods={"POST"}, defaults={"XmlHttpRequest"=true, "_captcha"=true})
     */
    public function sendPackageCollectionForm(RequestDataBag $data, SalesChannelContext $context): JsonResponse
    {
        $response = [];

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