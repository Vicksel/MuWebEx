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
    public $configuration   = NULL;
    public $settings        = NULL;
    public $costumSettings  = NULL;
    public $database        = NULL;
    public $loader          = NULL;

    public function __construct($name,$type)
    {
        if($type !== PLUGIN_TYPE_SYSTEM)
        {
            $config = array();

            require 'application/plugins/'.$type.'/'.$name.'/System.php';

            $this->$configuration   = $config;

            if($this->$configuration['requiresDatabase'] == true)
            {
                $this->database = new database();
            }

            if($this->$configuration['CostumSettings'])
            {
                $this->CSettings = simplexml_load_file('application/plugins/'.$type.'/'.$name.'/CSettings.xml');
            }
        }

        $this->loader   = new Loader();
        $this->template = new Template($name);

        $this->settings = simplexml_load_file("application/plugins/$type/$name/settings.xml");
    }
} 