<?php declare(strict_types = 1);

namespace SwagPackageCollection\Core\Content\Package;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class PackageEntity extends Entity
{
    use EntityIdTrait;

    protected $name;
    protected $height;
    protected $width;
    protected $length;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getHeight(): string
    {
        return $this->height;
    }

    public function setHeight($height): void
    {
        $this->height = $height;
    }

    public function getWidth(): string
    {
        return $this->width;
    }

    public function setWidth($width): void
    {
        $this->width = $width;
    }

    public function getLength(): string
    {
        return $this->length;
    }

    public function setlength($length): void
    {
        $this->length = $length;
    }
}