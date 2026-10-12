<?php

class Autoloader
{
    public static function register(): void
    {
        spl_autoload_register(function (string $class): void {
            // App\Article → App/Article.php
            $prefix = 'App\\';
            if (str_starts_with($class, $prefix)) {
                $class = 'src\\' .  substr($class, strlen($prefix));
            }
           
            $file = __DIR__ . DIRECTORY_SEPARATOR
                . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
            if (file_exists($file)) {
                require $file;
            }
        });
    }
}

Autoloader::register();
