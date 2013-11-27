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

class Controller
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
            $config = array();

            require 'application/plugins/'.$type.'/'.$name.'/system.php';

            $this->config   = $config;

            if($this->config['requiresDatabase'] == true)
            {
                $this->database = new database();
            }

            if($this->config['CostumSettings'])
            {
            //    $this->CSettings = simplexml_load_file('application/plugins/'.$type.'/'.$name.'/CSettings.xml');
            }
        }

        $this->loader   = new Loader();
        $this->template = new Template($name);

        $this->settings = simplexml_load_file("application/plugins/$type/$name/settings.xml");
    }
} 