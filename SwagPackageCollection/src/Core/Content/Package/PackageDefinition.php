<?php declare(strict_types = 1);

namespace SwagPackageCollection\Core\Content\Package;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class PackageDefinition extends EntityDefinition
{
    public function getEntityName(): string
    {
        return "swag_package";
    }

    public function getCollectionClass(): string
    {
        return PackageCollection::class;
    }

    public function getEntityClass(): string
    {
        return PackageEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new StringField('height', 'height'))->addFlags(new Required()),
            (new StringField('width', 'width'))->addFlags(new Required()),
            (new StringField('length', 'length'))->addFlags(new Required()),
        ]);
    }
}