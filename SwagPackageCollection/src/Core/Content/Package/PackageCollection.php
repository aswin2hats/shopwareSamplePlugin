<?php declare(strict_types = 1);

namespace SwagPackageCollection\Core\Content\Package;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class PackageCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return PackageEntity::class;
    }
}