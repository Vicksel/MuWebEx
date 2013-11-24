<?php
/**
 *  MuWebEx
 *
 *  Created by Kristians Jaunzems on 13.24.11.
 *  Copyright RealDev 2013. All rights reserved.
 *
 */

class system_template extends CModule implements IModuleMinEx
{
    protected $Content;

    public function __construct()
    {
        parent::__construct('template',PLUGIN_TYPE_SYSTEM);
    }


    public function Initialize()
    {

    }

    public function Execute()
    {
        $this->template->parseView('template',false);
    }
} 