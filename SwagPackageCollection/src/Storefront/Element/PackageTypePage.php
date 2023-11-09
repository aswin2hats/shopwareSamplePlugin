<?php

declare(strict_types=1);

namespace SwagPackageCollection\Storefront\Element;

use Shopware\Storefront\Page\Page;
use Shopware\Storefront\Framework\Page\StorefrontSearchResult;

class PackageTypePage extends Page
{
    protected StorefrontSearchResult $packageTypes;
    protected array $receiverEmail;

    protected ?string $searchTerm = null;
    protected ?string $navigationId = null;

    public function getPackageTypes(): StorefrontSearchResult
    {
        return $this->packageTypes;
    }

    public function setPackageTypes(StorefrontSearchResult $packageTypes): void
    {
        $this->packageTypes = $packageTypes;
    }

    public function getNavigationId(): ?string
    {
        return $this->navigationId;
    }

    public function setNavigationId(?string $navigationId): void
    {
        $this->navigationId = $navigationId;
    }

    public function getSearchTerm(): ?string
    {
        return $this->searchTerm;
    }

    public function setSearchTerm(?string $searchTerm): void
    {
        $this->searchTerm = $searchTerm;
    }

    public function getReceiverEmail(): array
    {
        return $this->receiverEmail;
    }

    public function setReceiverEmail(array $receiverEmail): void
    {
        $this->receiverEmail = $receiverEmail;
    }
}
