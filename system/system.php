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

// Configs
require 'system/configs/defines.php';
// Tools
require 'system/tools/input.php';       Input::Initialise();
require 'system/tools/toolbox.php';
// Engine
require 'system/engine/interfaces.php';
require 'system/engine/loader.php';