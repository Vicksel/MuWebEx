<?php
/**
 *  MuWebEx
 *
 *  Created by Kristians Jaunzems on 13.24.11.
 *  Copyright RealDev 2013. All rights reserved.
 *
 */

/**
 * Interface IModule
 */
interface IModule
{
    /**
     * Loads all required data for modules
     *
     */
    public function Initialize();

    /**
     * Inserts sensitive data to database
     *
     */
    public function SendData();

    /**
     * A custom data validation function where costum data is validated
     *
     * @return array on success
     *         false on failure
     *
     */
    public function ValidateData();

    /**
     * Main plugin execution function
     *
     */
    public function Execute();

    /**
     * Clears all data used by plugin
     *
     */
    public function Clear();
}

/**
 * Interface IModuleMin
 */
interface IModuleMin
{
    /**
     * Main plugin execution function
     *
     */
    public function Execute();
}

/**
 * IModuleMed
 */
interface IModuleMed
{
    /**
     * Inserts sensitive data to database
     *
     */
    public function SendData();

    /**
     * A custom data validation function where costum data is validated
     *
     * @return array on success
     *         false on failure
     *
     */
    public function ValidateData();

    /**
     * Main plugin execution function
     *
     */
    public function Execute();
}