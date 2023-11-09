<?php declare(strict_types=1);

namespace SwagPackageCollection\Core\Content\Package\Service;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Uninstaller
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(
        Connection $connection,
        protected EntityRepository $mailTemplateTranslationRepository,
        protected EntityRepository $mailTemplateRepository,
        protected EntityRepository $mailTemplateTypeRepository,
        ContainerInterface $container,
    ) {
        $this->connection = $connection;
        $this->mailTemplateRepository = $mailTemplateRepository;
        $this->mailTemplateTranslationRepository = $mailTemplateTranslationRepository;
        $this->mailTemplateTypeRepository = $mailTemplateTypeRepository;
        $this->container = $container;
    }

    /**
     * @param UninstallContext $uninstallContext
     */
    public function uninstall(UninstallContext $uninstallContext): void
    {
        if ($uninstallContext->keepUserData()) {
            return;
        }

        $this->removePackageTypeTable();
        $this->removePackageFormMailTemplateData();
        return;
    }

    private function removePackageTypeTable()
    {
        $tableName = 'swag_package';
        $sql = "DROP TABLE IF EXISTS $tableName";
        $this->connection->executeUpdate($sql);
    }

    private function removePackageFormMailTemplateData()
    {
        $mailTemplateRepository = $this->container->get('mail_template.repository');
        $mailTemplateTranslationRepository = $this->container->get('mail_template_translation.repository');

        $templateTechnicalName = 'package_collection_form';

        $mailTemplateTypeRepository = $this->container->get('mail_template_type.repository');

        $mailTemplateTypes = $mailTemplateTypeRepository->search(
            (new Criteria())->addFilter(new EqualsFilter('technicalName', $templateTechnicalName)),
            Context::createDefaultContext()
        );
        $mailTemplateType = $mailTemplateTypes->first();
        $mailTemplateTypeId = $mailTemplateType->getId();

        if($mailTemplateTypeId) {
            $criteria = new Criteria();
            $criteria->addFilter(new EqualsFilter('mailTemplateTypeId', $mailTemplateTypeId));

            $mailTemplates = $mailTemplateRepository->search($criteria, Context::createDefaultContext());

            foreach ($mailTemplates->getIds() as $mailTemplateId) {
                $mailTemplate = [
                    'id' => $mailTemplateId,
                ];

                $mailTemplateRepository->delete([$mailTemplate], Context::createDefaultContext());
            }

           $mailTemplateTypeRepository->delete([['id' => $mailTemplateTypeId]], Context::createDefaultContext());
        }
    }
}