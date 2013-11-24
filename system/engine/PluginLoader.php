<?php
/**
 *  MuWebEx
 *
 *  Created by Kristians Jaunzems on 13.24.11.
 *  Copyright RealDev 2013. All rights reserved.
 *
 */


/**
 * Class PluginLoader
 */
class PluginLoader
{
    /**
     * Holds current active plugins and library
     *
     * @var array
     */
    private static $Plugins = array();

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
     * Loads library into memory
     *
     * @param string $Name
     * @return mixed
     */
    public function LoadLibrary($Name)
    {
        if(!isset(self::$Plugins['library'][$Name]))
        {
            require "application/library/$Name/Module.php";

            $PluginName = 'library_'.$Name;

            self::$Plugins['library'][$Name] = new $PluginName();
        }

        return self::$Plugins['library'][$Name];
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