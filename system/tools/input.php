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

class Input
{
    /**
     * Holds secured user input using $_GET request
     *
     * @var array
     */
    public static $GET = array();

    /**
     * Holds secured user input using $_POST request
     *
     * @var array
     */
    public static $POST = array();

    /**
     *
     */
    public static function Initialise()
    {
        self::$GET = Security::CleanXSS($_GET);
        self::$POST = Security::CleanXSS($_POST);
    }

    /**
     * Returns secure variable from $_GET request
     *
     * @param string $key
     * @return false if variable do not exist
     *         value on success
     */
    public static function Get($key)
    {
        if(isset(self::$GET[$key]))
        {
            return self::$GET[$key];
        }
        return false;
    }

    /**
     * Overwrites selected key in post array
     *
     * @param $key
     * @param $value
     * @return bool
     */
    public static function OverwritePost($key,$value)
    {
        if(isset(self::$POST[$key]))
        {
            self::$POST[$key] = $value;
        }
        return false;
    }

    /**
     * Returns secure variable from $_POST request
     *
     * @param string $key
     * @return false if variable do not exist
     *         value on success
     */
    public static function Post($key)
    {
        if(isset(self::$POST[$key]))
        {
            return self::$POST[$key];
        }
        return false;
    }

}
