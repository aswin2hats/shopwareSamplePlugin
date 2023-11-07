<?php

declare(strict_types=1);

namespace SwagPackageCollection\Struct;

use Shopware\Core\Framework\Struct\Struct;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class PackageStruct extends Struct
{
    protected EntityCollection $packages;
    protected EntityCollection $salutations;

    public function getPackages(): EntityCollection
    {
        return $this->packages;
    }

    public function setPackages(EntityCollection $packages): void
    {
        $this->packages = $packages;
    }

    public function setSalutation(EntityCollection $salutations)
    {
        $this->salutations = $salutations;
    }

    public function getSalutation(): EntityCollection
    {
        return $this->salutations;
    }
}

