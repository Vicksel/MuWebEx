<?php

class blocks_test extends Controller implements IModuleMin
{
    public function __construct()
    {
        parent::__construct('test',PLUGIN_TYPE_BLOCKS);
    }

    public function Execute()
    {
        $this->template->prepareLanguage('home');
        return $this->loader->LoadLibrary('template')->writePanel($this->template->languageVariables['title'],$this->template->parseView('home'));
    }
} 