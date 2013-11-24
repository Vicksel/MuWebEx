<?php
/**
 *  MuWebEx
 *
 *  Created by Kristians Jaunzems on 13.24.11.
 *  Copyright RealDev 2013. All rights reserved.
 *
 */

session_start();
error_reporting(E_ALL);

/*
 * User Agent Check
 */
if (isset($_SESSION['HTTP_USER_AGENT']) AND $_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']))
{
    exit;
}
else
{
    $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
}

/*
 * Generate Initial Form Forgery Token
 */
if(!isset($_SESSION['token']))
{
    $_SESSION['token'] = md5(uniqid(rand(), true));
}

/*
 * System File Protection
 */
define('WEB_ENGINE','WEB_ENGINE');

/*
 * Include required files
 */
require 'system/configs/defines.php';

require 'system/tools/Toolbox.php';
require 'system/engine/Interfaces.php';
require 'system/engine/PluginLoader.php';