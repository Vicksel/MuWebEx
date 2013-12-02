<?php
/**
 *  MuWebEx
 *
 *  Created by Kristians Jaunzems on 13.24.11.
 *  Copyright RealDev 2013. All rights reserved.
 *
 */

if(!defined(MODULE_EXECUTE))
    die('Direct access to system modules is forbidden!');

class system_template extends Controller implements IModuleMinEx
{
    protected $TemplateWrapper;

    public function __construct()
    {
        parent::__construct('template',PLUGIN_TYPE_SYSTEM);
    }

    public function Initialize()
    {
        $this->TemplateWrapper  = $this->loader->LoadLibrary('template');

    }

    public function Execute()
    {
        $GlobalConfig     = $this->loader->LoadSetting('muwebex');


        $this->template->addLocalVariable('title',$GlobalConfig->site->title);
        $this->template->addLocalVariable('footer',$GlobalConfig->site->footer);

        return $this->template->parseView('template',false);
    }

    public function Error_Module_Valid()
    {
        $this->template->prepareLanguage('module_not_valid');

        $content =  $this->loader->LoadLibrary('template')->writePage($this->template->languageVariables['title'],$this->template->parseView('error_template',false));

        $this->template->addLocalVariable('content',$content);
    }

    public function Error_Module_Access()
    {
        $this->template->prepareLanguage('module_no_access');

        $this->template->addLocalVariable('required_access','admins');

        $content =  $this->loader->LoadLibrary('template')->writePage($this->template->languageVariables['title'],$this->template->parseView('error_template',false,true));

        $this->template->addLocalVariable('content',$content);
    }
} 