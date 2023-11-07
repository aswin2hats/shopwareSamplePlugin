<?php declare(strict_types=1);

namespace SwagPackageCollection\Core\Content\Package\Event;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Event\EventData\EventDataCollection;
use Shopware\Core\Framework\Event\EventData\MailRecipientStruct;
use Shopware\Core\Framework\Event\EventData\ObjectType;
use Shopware\Core\Framework\Event\FlowEventAware;
use Shopware\Core\Framework\Event\MailAware;
use Shopware\Core\Framework\Event\SalesChannelAware;
use Shopware\Core\Framework\Validation\DataBag\DataBag;
use SwagPackageCollection\Core\Content\Flow\Dispatching\Aware\PackageCollectionFormDataAware;
use Symfony\Contracts\EventDispatcher\Event;

final class PackageCollectionFormEvent extends Event implements FlowEventAware, SalesChannelAware, MailAware, PackageCollectionFormDataAware
{
    public const EVENT_NAME = 'package_collection_form.send';

    /**
     * @var Context
     */
    private $context;

    /**
     * @var string
     */
    private $salesChannelId;

    /**
     * @var MailRecipientStruct
     */
    private $recipients;

    /**
     * @var array<int|string, mixed>
     */
    private $packageCollectionFormData;

    public function __construct(Context $context, string $salesChannelId, MailRecipientStruct $recipients, DataBag $packageCollectionFormData)
    {
        $this->context = $context;
        $this->salesChannelId = $salesChannelId;
        $this->recipients = $recipients;
        $this->packageCollectionFormData = $packageCollectionFormData->all();
    }

    public static function getAvailableData(): EventDataCollection
    {
        return (new EventDataCollection())
            ->add('packageCollectionFormData', new ObjectType());
    }

    public function getName(): string
    {
        return self::EVENT_NAME;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function getMailStruct(): MailRecipientStruct
    {
        return $this->recipients;
    }

    public function getSalesChannelId(): string
    {
        return $this->salesChannelId;
    }

    /**
     * @return array<int|string, mixed>
     */
    public function getPackageCollectionFormData(): array
    {
        return $this->packageCollectionFormData;
    }
}