<?php
/**
 *  MuWebEx
 *
 *  Created by Kristians Jaunzems on 13.24.11.
 *  Copyright RealDev 2013. All rights reserved.
 *
 */

if(!defined(WEB_ENGINE))
    die('Direct access to system modules is forbidden!');

class CModule
{
    public $template        = NULL;
    public $config          = array();
    public $settings        = NULL;
    public $costumSettings  = NULL;
    public $database        = NULL;
    public $loader          = NULL;

    public function __construct($name,$type)
    {
        if($type !== PLUGIN_TYPE_SYSTEM)
        {
            require 'application/plugins/'.$type.'/'.$name.'/system.php';

            $this->config   = (array)$config;
        }

        $this->loader   = new Loader();
        $this->template = new Template($name);

        $this->settings = simplexml_load_file("application/plugins/$type/$name/settings.xml");
    }
} 