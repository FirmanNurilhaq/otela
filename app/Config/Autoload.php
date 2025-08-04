<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

class Autoload extends AutoloadConfig
{
    public $psr4 = [
        APP_NAMESPACE => APPPATH,
    ];

    public $classmap = [];

    /**
     * -------------------------------------------------------------------
     * Composer auto-loading
     * -------------------------------------------------------------------
     *
     * The Composer autoloader is automatically loaded if it exits.
     */
    public bool $composerAutoload = true;

    public $files = [];

    public $helpers = [];
}
