<?php

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