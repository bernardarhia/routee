<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit72f05f15d51085d6d987a37f1fe28a7f
{
    public static $fallbackDirsPsr4 = array (
        0 => __DIR__ . '/../..' . '/src',
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Routee\\Helpers\\Helpers' => __DIR__ . '/../..' . '/src/Routee/Helpers/Helpers.php',
        'Routee\\Http\\Request' => __DIR__ . '/../..' . '/src/Routee/Http/Request.php',
        'Routee\\Http\\Response' => __DIR__ . '/../..' . '/src/Routee/Http/Response.php',
        'Routee\\Http\\Router' => __DIR__ . '/../..' . '/src/Routee/Http/Router.php',
        'Routee\\View\\View' => __DIR__ . '/../..' . '/src/Routee/View/View.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->fallbackDirsPsr4 = ComposerStaticInit72f05f15d51085d6d987a37f1fe28a7f::$fallbackDirsPsr4;
            $loader->classMap = ComposerStaticInit72f05f15d51085d6d987a37f1fe28a7f::$classMap;

        }, null, ClassLoader::class);
    }
}
