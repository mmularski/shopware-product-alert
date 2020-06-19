<?php declare(strict_types=1);
/**
 * @package Divante\ProductAlert
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\ProductAlert\Migration;

use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use RuntimeException;
use Shopware\Core\Content\MailTemplate\MailTemplateActions;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

/**
 * Class Migration1589985183MailTemplate
 */
class Migration1589985183MailTemplate extends MigrationStep
{
    /**
     * @return int
     */
    public function getCreationTimestamp(): int
    {
        return 1589985183;
    }

    /**
     * @param Connection $connection
     *
     * @throws DBALException
     */
    public function update(Connection $connection): void
    {
        $mailTemplateTypeId = $this->createMailTemplateType($connection);

        $this->createMailTemplate($connection, $mailTemplateTypeId);
    }

    /**
     * @param Connection $connection
     * @param string $locale
     *
     * @return string|null
     *
     * @throws DBALException
     */
    private function getLanguageIdByLocale(Connection $connection, string $locale): ?string
    {
        $sql = <<<SQL
SELECT `language`.`id` 
FROM `language` 
INNER JOIN `locale` ON `locale`.`id` = `language`.`locale_id`
WHERE `locale`.`code` = :code
SQL;

        $languageId = $connection->executeQuery($sql, ['code' => $locale])->fetchColumn();

        if (!$languageId) {
            throw new RuntimeException(sprintf('Language for locale "%s" not found.', $locale));
        }

        return $languageId;
    }

    /**
     * @param Connection $connection
     *
     * @return string
     *
     * @throws DBALException
     */
    private function createMailTemplateType(Connection $connection): string
    {
        $mailTemplateTypeId = Uuid::randomHex();

        $connection->insert(
            'mail_template_type',
            [
                'id' => Uuid::fromHexToBytes($mailTemplateTypeId),
                'technical_name' => 'product.stock.alert',
                'available_entities' => json_encode(
                    [
                        'product' => 'product',
                        'salesChannel' => 'sales_channel',
                    ]
                ),
                'created_at' => (new DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ]
        );

        $connection->insert(
            'mail_template_type_translation',
            [
                'mail_template_type_id' => Uuid::fromHexToBytes($mailTemplateTypeId),
                'language_id' => $this->getLanguageIdByLocale($connection, 'en-GB'),
                'name' => 'Out of stock product notification',
                'created_at' => (new DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ]
        );

        return $mailTemplateTypeId;
    }

    /**
     * @param Connection $connection
     * @param string $mailTemplateTypeId
     *
     * @throws DBALException
     */
    private function createMailTemplate(Connection $connection, string $mailTemplateTypeId): void
    {
        $mailTemplateId = Uuid::randomHex();

        $connection->insert(
            'mail_template',
            [
                'id' => Uuid::fromHexToBytes($mailTemplateId),
                'mail_template_type_id' => Uuid::fromHexToBytes($mailTemplateTypeId),
                'system_default' => 0,
                'created_at' => (new DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ]
        );

        $connection->insert(
            'mail_template_translation',
            [
                'mail_template_id' => Uuid::fromHexToBytes($mailTemplateId),
                'language_id' => $this->getLanguageIdByLocale($connection, 'en-GB'),
                'sender_name' => '{{ shopName }}',
                'subject' => 'Product {{ product.name }} is back to stock!',
                'description' => '',
                'content_html' => $this->getContentHtmlEn(),
                'content_plain' => $this->getContentPlainEn(),
                'created_at' => (new DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ]
        );

        $this->addTemplateToSalesChannels($connection, $mailTemplateTypeId, $mailTemplateId);
    }

    /**
     * @param Connection $connection
     * @param string $mailTemplateTypeId
     * @param string $mailTemplateId
     *
     * @throws DBALException
     */
    private function addTemplateToSalesChannels(
        Connection $connection,
        string $mailTemplateTypeId,
        string $mailTemplateId
    ): void {
        $salesChannels = $connection->fetchAll('SELECT `id` FROM `sales_channel` ');

        foreach ($salesChannels as $salesChannel) {
            $mailTemplateSalesChannel = [
                'id' => Uuid::randomBytes(),
                'mail_template_id' => Uuid::fromHexToBytes($mailTemplateId),
                'mail_template_type_id' => Uuid::fromHexToBytes($mailTemplateTypeId),
                'sales_channel_id' => $salesChannel['id'],
                'created_at' => (new DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ];

            $connection->insert('mail_template_sales_channel', $mailTemplateSalesChannel);
        }
    }

    /**
     * @return string
     */
    private function getContentHtmlEn(): string
    {
        return <<<MAIL
<div style="font-family:arial; font-size:12px;">
    <p>
        Hello!<br/>
        <br/>
        Product {{ product.name }} is available in our store!
        <br/>
        <br/>
        Yours sincerely
        Your {{ shopName }} team
    </p>
</div>
MAIL;
    }

    /**
     * @return string
     */
    private function getContentPlainEn(): string
    {
        return <<<MAIL
        Hello!
        
        Product {{ product.name }} is available in our store!
        
        Yours sincerely
        Your {{ shopName }}-Team
MAIL;
    }

    /**
     * @inheritDoc
     */
    public function updateDestructive(Connection $connection): void
    {
    }
}
