<?php

if(!defined(MODULE_EXECUTE))
    die('Direct access to system modules is forbidden!');

class modules_home extends Controller implements IModuleMin
{
    public function __construct()
    {
        parent::__construct('home',PLUGIN_TYPE_MODULES);
    }


    public function Execute()
    {
        $this->template->prepareLanguage('home');
        return $this->loader->LoadLibrary('template')->writePage($this->template->languageVariables['title'],$this->template->parseView('home'));
    }
} 