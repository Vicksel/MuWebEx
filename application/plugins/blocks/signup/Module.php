<?php
/**
 *  MuWebEx
 *
 *  Created by Kristians Jaunzems on 13.26.11.
 *  Copyright RealDev 2013. All rights reserved.
 *
 */

if(!defined(MODULE_EXECUTE))
    die('Direct access to system modules is forbidden!');

class blocks_signup extends Controller implements IModuleMin
{
    public function __construct()
    {
        parent::__construct('signup',PLUGIN_TYPE_BLOCKS);
    }

    public function Execute()
    {
        $this->template->prepareLanguage('block_signup');
        return $this->loader->LoadLibrary('template')->writePage($this->template->languageVariables['title'],$this->template->parseView('block_signup'));
    }
} 