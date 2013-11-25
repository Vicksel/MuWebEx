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
 * Default User Language
 */
if(!isset($_SESSION['language']))
{
    $_SESSION['language'] = 'english';
}

/*
 * Default User Access
 */
if(!isset($_SESSION['access']))
{
    $_SESSION['access'] = 'guests';
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
require 'system/tools/cache.php';
require 'system/tools/template.php';
require 'system/tools/security.php';
require 'system/tools/input.php';       Input::Initialise();
require 'system/tools/toolbox.php';
require 'system/tools/database.php';    $_SESSION['database'] = Database::initialize();

// Engine
require 'system/engine/interfaces.php';
require 'system/engine/loader.php';
require 'system/engine/controller.php';
require 'system/engine/execute.php';

Execute::Plugin();