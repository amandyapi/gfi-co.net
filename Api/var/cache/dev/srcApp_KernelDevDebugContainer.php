<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerYouparl\srcApp_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerYouparl/srcApp_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerYouparl.legacy');

    return;
}

if (!\class_exists(srcApp_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerYouparl\srcApp_KernelDevDebugContainer::class, srcApp_KernelDevDebugContainer::class, false);
}

return new \ContainerYouparl\srcApp_KernelDevDebugContainer([
    'container.build_hash' => 'Youparl',
    'container.build_id' => '06a82bf8',
    'container.build_time' => 1642416454,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerYouparl');
