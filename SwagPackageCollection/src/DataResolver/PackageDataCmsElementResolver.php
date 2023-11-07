<?php declare(strict_types = 1);

namespace SwagPackageCollection\DataResolver;

use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SwagPackageCollection\Struct\PackageStruct;

class PackageDataCmsElementResolver extends AbstractCmsElementResolver
{
    private $packageRepository;
    private $salutationRepository;

    public function __construct(EntityRepositoryInterface $packageRepository, EntityRepositoryInterface $salutationRepository)
    {
        $this->packageRepository = $packageRepository;
        $this->salutationRepository = $salutationRepository;
    }

    public function getType(): string
    {
        return 'collection-form';
    }

    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {
        $collection = new CriteriaCollection();

        return $collection;
    }

    public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
        $data = new PackageStruct();
        $criteria = new Criteria();
        $packages = [];
        $packages =  $this->packageRepository->search($criteria, $resolverContext->getSalesChannelContext()->getContext());
        $packages  = $packages ?  $packages->getEntities() : [];

        $salutationCriteria = new Criteria();
        $salutations = $this->salutationRepository->search($salutationCriteria, $resolverContext->getSalesChannelContext()->getContext());
        $salutations  = $salutations ?  $salutations->getEntities() : [];

        $data->setPackages($packages);
        $data->setSalutation($salutations);
        $slot->setData($data);
    }
}