<?php

declare(strict_types=1);

namespace SwagPackageCollection\Storefront\Element;

use Symfony\Component\HttpFoundation\Request;
use Shopware\Storefront\Page\GenericPageLoader;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Framework\Page\StorefrontSearchResult;
use Shopware\Core\Content\Cms\DataResolver\FieldConfigCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;

class PackageTypePageLoader
{
    private GenericPageLoader $genericLoader;
    private EntityRepository $packageTypeRepository;

    public function __construct(
        EntityRepository $packageTypeRepository,
        GenericPageLoader $genericLoader
    ) {
        $this->packageTypeRepository = $packageTypeRepository;
        $this->genericLoader = $genericLoader;
    }

    public function load(Request $request, SalesChannelContext $salesChannelContext, FieldConfigCollection $config): PackageTypePage
    {
        $page = $this->genericLoader->load($request, $salesChannelContext);
        $page = PackageTypePage::createFrom($page);
        $page->setNavigationId($request->get('navigationId'));
        $searchTerm = $request->query->get('searchTerm');

        if (!empty($searchTerm)) {
            $page->setSearchTerm($searchTerm);
        }

        $packageTypes = $this->packageTypeRepository->search(
            $this->createCriteria($config, $request, $page),
            $salesChannelContext->getContext()
        );
        $page->setPackageTypes(StorefrontSearchResult::createFrom($packageTypes));
        $this->setReceiverEmail($page, $config);

        return $page;
    }

    private function createCriteria(FieldConfigCollection $config, Request $request, PackageTypePage $page): Criteria
    {
        $criteria = new Criteria();
        return $criteria;
    }

    private function setReceiverEmail(PackageTypePage $page, FieldConfigCollection $config): PackageTypePage
    {
        $receiverEmail[] = $config->get('mailReceiver')->getValue();
        $page->setReceiverEmail($receiverEmail);
        return $page;
    }
}
