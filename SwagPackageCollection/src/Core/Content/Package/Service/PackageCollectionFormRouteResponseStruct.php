<?php declare(strict_types=1);

namespace SwagPackageCollection\Core\Content\Package\Service;

use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Struct\Struct;

#[Package('content')]
class PackageCollectionFormRouteResponseStruct extends Struct
{
    /**
     * @var string
     */
    protected $individualSuccessMessage;

    public function getApiAlias(): string
    {
        return 'package_collection_form_result';
    }

    public function getIndividualSuccessMessage(): string
    {
        return $this->individualSuccessMessage;
    }
}