<?php declare(strict_types=1);

namespace SwagPackageCollection\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1699515045PackageCollectionForm extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1699515045;
    }

    public function update(Connection $connection): void
    {
        $packageFormEmailTemplate = [
            'id' => Uuid::randomHex(),
            'name' => 'Package form',
            'nameDe' => 'Kontaktformular',
            'availableEntities' => json_encode(['salesChannel' => 'sales_channel']),
        ];

        $technicalName = 'package_collection_form';
        $sql = 'SELECT id FROM mail_template_type WHERE technical_name = :technicalName';
        $existingRecord = $connection->executeQuery($sql, ['technicalName' => $technicalName])->fetchOne();

        if (!$existingRecord) {
            $mailTemplateTypeId = Uuid::fromHexToBytes($packageFormEmailTemplate['id']);
            $connection->insert(
                'mail_template_type',
                [
                    'id' => $mailTemplateTypeId,
                    'technical_name' => 'package_collection_form',
                    'available_entities' => $packageFormEmailTemplate['availableEntities'],
                    'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
                ]
            );

            $defaultLangId = $this->getLanguageIdByLocale($connection, 'en-GB');
            $deLangId = $this->getLanguageIdByLocale($connection, 'de-DE');

            if ($defaultLangId !== $deLangId) {
                $connection->insert(
                    'mail_template_type_translation',
                    [
                        'mail_template_type_id' => $mailTemplateTypeId,
                        'name' => $packageFormEmailTemplate['name'],
                        'language_id' => $defaultLangId,
                        'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
                    ]
                );
            }

            if ($defaultLangId !== Uuid::fromHexToBytes(Defaults::LANGUAGE_SYSTEM)) {
                $connection->insert(
                    'mail_template_type_translation',
                    [
                        'mail_template_type_id' => $mailTemplateTypeId,
                        'name' => $packageFormEmailTemplate['name'],
                        'language_id' => Uuid::fromHexToBytes(Defaults::LANGUAGE_SYSTEM),
                        'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
                    ]
                );
            }

            if ($deLangId) {
                $connection->insert(
                    'mail_template_type_translation',
                    [
                        'mail_template_type_id' => $mailTemplateTypeId,
                        'name' => $packageFormEmailTemplate['nameDe'],
                        'language_id' => $deLangId,
                        'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
                    ]
                );
            }
        }
    }

    private function getLanguageIdByLocale(Connection $connection, string $locale): ?string
    {
        $sql = <<<'SQL'
        SELECT `language`.`id`
        FROM `language`
        INNER JOIN `locale` ON `locale`.`id` = `language`.`locale_id`
        WHERE `locale`.`code` = :code
        SQL;

        /** @var string|false $languageId */
        $languageId = $connection->executeQuery($sql, ['code' => $locale])->fetchOne();
        if (!$languageId && $locale !== 'en-GB') {
            return null;
        }

        if (!$languageId) {
            return Uuid::fromHexToBytes(Defaults::LANGUAGE_SYSTEM);
        }

        return $languageId;
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
