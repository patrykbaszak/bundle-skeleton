<?php

declare(strict_types=1);

namespace $NAMESPACE;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class $PACKAGEBundle extends Bundle
{
    public const ALIAS = '$LC_VENDOR.$LC_PACKAGE';

    public function getContainerExtension(): ExtensionInterface
    {
        return $this->extension ??= new DependencyInjection\$PACKAGEExtension();
    }
}
