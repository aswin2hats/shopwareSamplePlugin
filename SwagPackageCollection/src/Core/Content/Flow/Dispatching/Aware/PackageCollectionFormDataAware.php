<?php declare(strict_types=1);

namespace SwagPackageCollection\Core\Content\Flow\Dispatching\Aware;

use Shopware\Core\Framework\Event\FlowEventAware;
use Shopware\Core\Framework\Log\Package;

#[Package('business-ops')]
interface PackageCollectionFormDataAware extends FlowEventAware
{
    public const PACKAGE_COLLECTION_FORM_DATA = 'packageCollectionFormData';

    /**
     * @return array<string, mixed>
     */
    public function getPackageCollectionFormData(): array;
}