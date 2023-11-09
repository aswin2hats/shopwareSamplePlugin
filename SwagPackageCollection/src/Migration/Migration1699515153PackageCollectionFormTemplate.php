<?php declare(strict_types=1);

namespace SwagPackageCollection\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1699515153PackageCollectionFormTemplate extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1699515153;
    }

    public function update(Connection $connection): void
    {
        $packageTemplateId = $this->getPackageMailTemplateId($connection);
        $packageTemplateTypeId = $this->getPackageMailEventConfig($connection);
        $update = false;
        if (!$packageTemplateId) {
            $packageTemplateId = Uuid::randomBytes();
        } else {
            $update = true;
        }

        if (!\is_string($packageTemplateId)) {
            return;
        }

        if ($update === true) {
            $connection->update(
                'mail_template',
                [
                    'updated_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
                ],
                ['id' => $packageTemplateId]
            );

            $connection->delete('mail_template_translation', ['mail_template_id' => $packageTemplateId]);
        } else {
            $connection->insert(
                'mail_template',
                [
                    'id' => $packageTemplateId,
                    'mail_template_type_id' => $packageTemplateTypeId,
                    'system_default' => 1,
                    'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
                ]
            );
        }

        $connection->insert(
            'mail_template_translation',
            [
                'subject' => 'Package collection form received - {{ salesChannel.name }}',
                'description' => 'Package collection form received',
                'sender_name' => '{{ salesChannel.name }}',
                'content_html' => $this->getPackageCollectionHtmlTemplateEn(),
                'content_plain' => $this->getPackageCollectionTemplateEn(),
                'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
                'mail_template_id' => $packageTemplateId,
                'language_id' => Uuid::fromHexToBytes(Defaults::LANGUAGE_SYSTEM),
            ]
        );
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }

    private function getPackageMailEventConfig(Connection $connection): string
    {
        $sql = <<<'SQL'
        SELECT `mail_template_type`.`id`
        FROM `mail_template_type`
        WHERE `mail_template_type`.`technical_name` = :technical_name
        SQL;

        $packageEventConfig = (string) $connection->executeQuery(
            $sql,
            [
                'technical_name' => 'package_collection_form',
            ]
        )->fetchOne();
        return $packageEventConfig;
    }

    private function getPackageMailTemplateId(Connection $connection): ?string
    {
        $sql = <<<'SQL'
        SELECT `mail_template`.`id`
        FROM `mail_template` LEFT JOIN `mail_template_type` ON `mail_template`.`mail_template_type_id` = `mail_template_type`.id
        WHERE `mail_template_type`.`technical_name` = :technical_name
    SQL;

        $templateTypeId = $connection->executeQuery(
            $sql,
            [
                'technical_name' => 'package_collection_form',
            ]
        )->fetchOne();

        if ($templateTypeId) {
            return $templateTypeId;
        }

        return null;
    }

    private function getPackageCollectionHtmlTemplateEn(): string
    {
        return
        '<div style="font-family:arial; font-size:18px;">
            <p>
                Message from {{ packageCollectionFormData.name }}  via the package collection form.<br/>
                <br/>
                email address: {{ packageCollectionFormData.email }}<br/>
                Phone: {{ packageCollectionFormData.phone }}<br/><br/>

                Address:
                Street: {{ packageCollectionFormData.street }}<br/>
                City: {{ packageCollectionFormData.city }}<br/>
                Zip Code: {{ packageCollectionFormData.zip_code }}<br/>
                Collection Date: {{ packageCollectionFormData.collection_date }}<br/>
                <br/>
                Packages:
                <ul>
                {% for packageType, quantity in packageCollectionFormData.packageArr %}
                    <li>{{ packageType }} - {{ quantity }}</li>
                {% endfor %}
                </ul>
                {% if packageCollectionFormData.packageTypeData %}
                <table style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <th style="border: 1px solid #000; padding: 5px;">Package Type</th>
                        <th style="border: 1px solid #000; padding: 5px;">Height</th>
                        <th style="border: 1px solid #000; padding: 5px;">Width</th>
                        <th style="border: 1px solid #000; padding: 5px;">Length</th>
                    </tr>
                    {% for packageType, data in packageCollectionFormData.packageTypeData %}
                        <tr>
                            <td style="border: 1px solid #000; padding: 5px;">{{ packageType }}</td>
                            <td style="border: 1px solid #000; padding: 5px;">{{ data.height }}</td>
                            <td style="border: 1px solid #000; padding: 5px;">{{ data.width }}</td>
                            <td style="border: 1px solid #000; padding: 5px;">{{ data.length }}</td>
                        </tr>
                    {% endfor %}
                </table>
            {% endif %}
            </p>
        </div>';
    }

    private function getPackageCollectionTemplateEn(): string
    {
        return 'Message from {{ packageCollectionFormData.name }} via the package form.

                Package email address: {{ packageCollectionFormData.email }}
                Phone: {{ packageCollectionFormData.phone }}

                Address:
                Street: {{ packageCollectionFormData.street }}
                City: {{ packageCollectionFormData.city }}
                Zip Code: {{ packageCollectionFormData.zip_code }}
                Collection Date: {{ packageCollectionFormData.collection_date }}

                Message:"Package collection form data sent"';
    }
}
