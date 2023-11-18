<?php

declare(strict_types=1);

$name = $argv[1];
if (!$name || false === strpos($name, '/')) {
    echo 'Usage: php scripts/Setup.php vendor/package' . PHP_EOL;
    exit(1);
}

[$vendor, $package] = explode('/', $name);
$composer  = json_decode(file_get_contents(__DIR__ . '/../skeleton/composer.json'));

$title = implode(' ', array_map(fn (string $s) => ucfirst($s), explode(' ', str_replace(['-', '_'], ' ', $vendor . ' ' . $package))));
$namespace = implode('', array_map(fn (string $s) => ucfirst($s), explode('-', $vendor))) . '\\' . implode('', array_map(fn (string $s) => ucfirst($s), explode('-', $package)));

// setup composer
$composer->name = $name;
$composer->title ??= $title;
$composer->description ??= $title . ' - Library';
$composer->autoload->{'psr-4'} = (object) [$namespace . '\\' => 'src/'];
$composer->{'autoload-dev'}->{'psr-4'} = (object) [$namespace . '\\Tests\\' => 'tests/'];
$composer->type = 'library';
$composer->scripts->{'auto-scripts'} = (object) ['cache:clear' => 'symfony-cmd'];

file_put_contents(__DIR__ . '/../skeleton/composer.json', json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

// setup namespace in files
$files = [
    dirname(__DIR__) . '/skeleton/src/Kernel.php',
    dirname(__DIR__) . '/skeleton/src/SkeletonBundle.php',
    dirname(__DIR__) . '/skeleton/src/DepedencyInjection/SkeletonExtension.php',
    dirname(__DIR__) . '/skeleton/bin/console',
];

foreach ($files as $file) {
    $content = file_get_contents($file);
    $content = str_replace(
        [
            '$NAMESPACE',
            '$PACKAGE',
            '$VENDOR',
            '$PACKAGE_LC',
            '$VENDOR_LC',
        ], 
        [
            $namespace,
            implode('', array_map(fn (string $s) => ucfirst($s), explode('-', $package))),
            implode('', array_map(fn (string $s) => ucfirst($s), explode('-', $vendor))),
            strtolower(implode('_', explode('-', $package))),
            strtolower(implode('_', explode('-', $vendor))),
        ], 
        $content
    );
    if (in_array($file, [
        dirname(__DIR__) . '/skeleton/src/DepedencyInjection/SkeletonExtension.php',
        dirname(__DIR__) . '/skeleton/src/SkeletonBundle.php',
    ])) {
        $file = str_replace('Skeleton', implode('', array_map(fn (string $s) => ucfirst($s), explode('-', $package))), $file);
    }
    file_put_contents($file, $content);
}

$content = file_get_contents(dirname(__DIR__) . '/skeleton/.env.test');
$content = str_replace('App\Kernel', $namespace . '\\Kernel', $content);
file_put_contents(dirname(__DIR__) . '/skeleton/.env.test', $content);
