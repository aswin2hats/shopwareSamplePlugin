<?php declare(strict_types=1);

namespace SwagPackageCollection;

use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use SwagPackageCollection\Core\Content\Package\Service\Uninstaller;

class SwagPackageCollection extends Plugin
{
    public function uninstall(UninstallContext $context): void
    {
        $unInstaller = new Uninstaller(
            $this->container->get(Connection::class),
            $this->container->get('mail_template.repository'),
            $this->container->get('mail_template_translation.repository'),
            $this->container->get('mail_template_type.repository'),
            $this->container
        );

        $unInstaller->uninstall($context);
    }
}