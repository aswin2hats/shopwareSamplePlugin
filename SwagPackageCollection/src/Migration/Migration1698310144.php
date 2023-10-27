<?php declare(strict_types=1);

namespace SwagPackageCollection\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1698310144 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1698310144;
    }

    public function update(Connection $connection): void
    {
        $connection->exec("CREATE TABLE IF NOT EXISTS `swag_package` (
            `id` BINARY(16) NOT NULL,
            `name` VARCHAR(100) NOT NULL,
            `height` VARCHAR(10) NOT NULL,
            `width` VARCHAR(10) NOT NULL,
            `length` VARCHAR(10) NOT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
