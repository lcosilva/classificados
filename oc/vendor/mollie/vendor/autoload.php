<?php

// scoper-composer-autoload.php @generated by PhpScoper

$loader = require_once __DIR__.'/composer-autoload.php';

// Aliases for the whitelisted classes. For more information see:
// https://github.com/humbug/php-scoper/blob/master/README.md#class-whitelisting
if (!class_exists('ComposerAutoloaderInit6e8ba4c83c5e359040bd0d6044d32978', false) && !interface_exists('ComposerAutoloaderInit6e8ba4c83c5e359040bd0d6044d32978', false) && !trait_exists('ComposerAutoloaderInit6e8ba4c83c5e359040bd0d6044d32978', false)) {
    spl_autoload_call('_PhpScopereac699eb1a3f\ComposerAutoloaderInit6e8ba4c83c5e359040bd0d6044d32978');
}
if (!class_exists('Normalizer', false) && !interface_exists('Normalizer', false) && !trait_exists('Normalizer', false)) {
    spl_autoload_call('_PhpScopereac699eb1a3f\Normalizer');
}

// Functions whitelisting. For more information see:
// https://github.com/humbug/php-scoper/blob/master/README.md#functions-whitelisting
if (!function_exists('database_write')) {
    function database_write() {
        return \_PhpScopereac699eb1a3f\database_write(...func_get_args());
    }
}
if (!function_exists('database_read')) {
    function database_read() {
        return \_PhpScopereac699eb1a3f\database_read(...func_get_args());
    }
}
if (!function_exists('printOrders')) {
    function printOrders() {
        return \_PhpScopereac699eb1a3f\printOrders(...func_get_args());
    }
}
if (!function_exists('composerRequire6e8ba4c83c5e359040bd0d6044d32978')) {
    function composerRequire6e8ba4c83c5e359040bd0d6044d32978() {
        return \_PhpScopereac699eb1a3f\composerRequire6e8ba4c83c5e359040bd0d6044d32978(...func_get_args());
    }
}
if (!function_exists('uri_template')) {
    function uri_template() {
        return \_PhpScopereac699eb1a3f\uri_template(...func_get_args());
    }
}

return $loader;
