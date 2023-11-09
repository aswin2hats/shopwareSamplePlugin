<?php declare(strict_types=1);

namespace SwagPackageCollection\Core\Content\Package\Service;

use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

abstract class AbstractPackageCollectionFormRoute
{
    abstract public function getDecorated(): AbstractPackageCollectionFormRoute;

    abstract public function load(RequestDataBag $data, SalesChannelContext $context): PackageCollectionFormResponse;
}