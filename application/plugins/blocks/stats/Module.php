<?php

class blocks_stats extends Controller implements IModuleMin
{
    public function __construct()
    {
        parent::__construct('stats',PLUGIN_TYPE_BLOCKS);
    }

    public function Execute()
    {
        $this->template->prepareLanguage('block_stats');
        return $this->loader->LoadLibrary('template')->writePage($this->template->languageVariables['title'],$this->template->parseView('block_stats'));
    }
} 