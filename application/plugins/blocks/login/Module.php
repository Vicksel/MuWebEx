<?php

class blocks_login extends Controller implements IModuleMin
{
    public function __construct()
    {
        parent::__construct('login',PLUGIN_TYPE_BLOCKS);
    }

    public function Execute()
    {
        $this->template->prepareLanguage('block_login');
        return $this->loader->LoadLibrary('template')->writePanel($this->template->languageVariables['title'],$this->template->parseView('block_login'));
    }
} 