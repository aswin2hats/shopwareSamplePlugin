<?php declare(strict_types = 1);

use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Content\Cms\SalesChannel\Struct\TextStruct;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

class PackageDataCmsElementResolver extends AbstractCmsElementResolver
{
    private $packageRepository;

    public function __construct(EntityRepositoryInterface $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    public function getType(): string
    {
        return 'collection-form';
    }

    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {
        $config = $slot->getFieldConfig();
        $collection = new CriteriaCollection();

        $warehouses = $config->get('mailReceiver');
        if ($warehouses === null) {
            return null;
        }

        return $collection;
    }

    public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
        $config = $slot->getFieldConfig();
        $slider = new TextStruct();
        $slot->setData($slider);
        $warehouseConfig = $config->get('warehouses');

        if ($warehouseConfig === null) {
            return;
        }

        $packages = $this->getPackages($resolverContext);
        $packages  = $packages ?  ($packages->getEntities() ? $packages->getEntities()->getElements() : []) : [];
        $slider->setExtensions($packages);
    }

    public function getPackages(ResolverContext $resolverContext)
    {
        $criteria = new Criteria();
        return $this->packageRepository->search($criteria, $resolverContext->getSalesChannelContext()->getContext());
    }
}