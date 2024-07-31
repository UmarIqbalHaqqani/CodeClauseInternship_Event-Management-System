<?php
namespace Eventin\Admin;

use Eventin\Abstracts\Provider;
use Eventin\Integrations\Integration;

/**
 * Admin Provider class
 * 
 * @package Eventin/Admin
 */

class AdminProvider extends Provider {
    /**
     * Holds classes that should be instantiated
     *
     * @var array
     */
    protected $services = [
        Integration::class,
    ];
}
