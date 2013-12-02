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

/**
 * Class PluginLoader
 */
class Loader
{
    /**
     * Holds current active plugins and library
     *
     * @var array
     */
    public static $Plugins = array();

    /**
     * Loads plugin into memory
     *
     * @param string $Name
     * @param string $Type
     * @return mixed
     */
    public function LoadPlugin($Name,$Type)
    {
        if(!isset(self::$Plugins[$Type][$Name]))
        {
            require "application/plugins/$Type/$Name/Module.php";

            $PluginName = $Type.'_'.$Name;

            self::$Plugins[$Type][$Name] = new $PluginName();
        }

        return self::$Plugins[$Type][$Name];
    }

    /**
     * Loads config into memory
     *
     * @param string $Name
     * @return mixed
     */
    public function LoadConfig($Name)
    {
        if(!isset(self::$Plugins['Configs'][$Name]))
        {
            $config = array();

            require "application/configs/$Name.php";

            self::$Plugins['Configs'][$Name] = $config;
        }

        return self::$Plugins['Configs'][$Name];
    }

    /**
     * Loads config into memory
     *
     * @param string $Name
     * @return mixed
     */
    public function LoadSetting($Name)
    {
        if(!isset(self::$Plugins['Settings'][$Name]))
        {
            self::$Plugins['Settings'][$Name] = simplexml_load_file("application/config/$Name.xml");
        }

        return self::$Plugins['Settings'][$Name];
    }

    /**
     * Loads library into memory
     *
     * @param string $Name
     * @return mixed
     */
    public function LoadLibrary($Name)
    {
        if(!isset(self::$Plugins['library'][$Name]))
        {
            require "application/libraries/$Name.php";

            $PluginName = 'library_'.$Name;

            self::$Plugins['library'][$Name] = new $PluginName();
        }

        return self::$Plugins['library'][$Name];
    }

    /**
     * Loads library into memory
     *
     * @param string $Name
     * @return mixed
     */
    public static function LoadLibraryEx($Name)
    {
        if(!isset(self::$Plugins['library'][$Name]))
        {
            require "application/libraries/$Name.php";

            $PluginName = 'library_'.$Name;

            self::$Plugins['library'][$Name] = new $PluginName();
        }

        return self::$Plugins['library'][$Name];
    }

    /**
     * Loads language file into memory
     *
     * Used for internal engine translations
     *
     * @param string $Name
     * @return mixed
     */
    public static function LoadLanguage($Name)
    {
        if(!isset(self::$Plugins['language'][$Name]))
        {
            $language = array();

            require "application/languages/".$_SESSION['language']."/$Name.php";

            self::$Plugins['language'][$Name] = $language;
        }

        return self::$Plugins['language'][$Name];
    }

    /**
     * Get's a list of currently loaded plugins or libraries
     *
     * @param string $Type
     * @return array
     */
    public function getLoaded($Type)
    {
        return array_keys(self::$Plugins[$Type]);
    }

    /**
     * Checks if selected plugin is loaded
     *
     * @param string $Name
     * @param string $Type
     * @return bool
     */
    public function isLoaded($Name,$Type)
    {
        if(!isset(self::$Plugins[$Type][$Name]))
        {
            return false;
        }
        return true;
    }

    /**
     * Unloads plugin from memory
     *
     * @param string $Name
     * @param string $Type
     */
    public function Unload($Name,$Type)
    {
        unset(self::$Plugins[$Type][$Name]);
    }
}