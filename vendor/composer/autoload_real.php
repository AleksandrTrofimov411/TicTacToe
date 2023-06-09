<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitbc841585c06a9f99747a34b5f6bad1eb
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitbc841585c06a9f99747a34b5f6bad1eb', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitbc841585c06a9f99747a34b5f6bad1eb', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitbc841585c06a9f99747a34b5f6bad1eb::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
