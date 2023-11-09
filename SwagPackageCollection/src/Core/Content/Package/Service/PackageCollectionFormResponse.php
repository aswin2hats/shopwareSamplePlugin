<?php declare(strict_types=1);

namespace SwagPackageCollection\Core\Content\Package\Service;

use Shopware\Core\Framework\Log\Package;
use Shopware\Core\System\SalesChannel\StoreApiResponse;

#[Package('content')]
class PackageCollectionFormResponse extends StoreApiResponse
{
    /**
     * @var PackageCollectionFormRouteResponseStruct
     */
    protected $object;

    public function __construct(PackageCollectionFormRouteResponseStruct $object)
    {
        parent::__construct($object);
    }

    public function getResult(): PackageCollectionFormRouteResponseStruct
    {
        return $this->object;
    }
}