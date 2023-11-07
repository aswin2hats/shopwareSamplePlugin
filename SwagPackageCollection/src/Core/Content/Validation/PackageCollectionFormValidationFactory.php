<?php declare(strict_types=1);

use Shopware\Core\Framework\DataAbstractionLayer\Validation\EntityExists;
use Shopware\Core\Framework\Validation\BuildValidationEvent;
use Shopware\Core\Framework\Validation\DataBag\DataBag;
use Shopware\Core\Framework\Validation\DataValidationDefinition;
use Shopware\Core\Framework\Validation\DataValidationFactoryInterface;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class PackageCollectionFormValidationFactory extends DataValidationFactoryInterface
{
    /**
     * The regex to check if string contains an url
     */
    public const DOMAIN_NAME_REGEX = '/((https?:\/\/))/';

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var SystemConfigService
     */
    private $systemConfigService;

    /**
     * @internal
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        SystemConfigService $systemConfigService
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->systemConfigService = $systemConfigService;
    }

    public function create(SalesChannelContext $context): DataValidationDefinition
    {
        return $this->createPackageCollectionFormValidation('package_collection_form.create', $context);
    }

    public function update(SalesChannelContext $context): DataValidationDefinition
    {
        return $this->createPackageCollectionFormValidation('package_collection_form.update', $context);
    }

    private function createPackageCollectionFormValidation(string $validationName, SalesChannelContext $context): DataValidationDefinition
    {
        $definition = new DataValidationDefinition($validationName);

        $definition
            ->add('salutationId', new NotBlank(), new EntityExists(['entity' => 'salutation', 'context' => $context->getContext()]))
            ->add('name', new NotBlank(), new Regex(['pattern' => self::DOMAIN_NAME_REGEX, 'match' => false]))
            ->add('email', new NotBlank(), new Email())
            ->add('phone', new NotBlank())
            ->add('street', new NotBlank(), new Regex(['pattern' => self::DOMAIN_NAME_REGEX, 'match' => false]))
            ->add('city', new NotBlank(), new Regex(['pattern' => self::DOMAIN_NAME_REGEX, 'match' => false]))
            ->add('zip_code', new NotBlank())
            ->add('collection_date', new NotBlank())
            ->add('package_type', new NotBlank())
            ->add('quantity', new NotBlank());

        $validationEvent = new BuildValidationEvent($definition, new DataBag(), $context->getContext());
        $this->eventDispatcher->dispatch($validationEvent, $validationEvent->getName());

        return $definition;
    }
}