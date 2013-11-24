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

    public static $globalSettings = NULL;


} 