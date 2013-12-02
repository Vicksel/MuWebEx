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

class blocks_login extends Controller implements IModuleMedEx
{
    public function __construct()
    {
        parent::__construct('login',PLUGIN_TYPE_BLOCKS);
    }

    public function ValidateData()
    {
        $valid = NULL;

        $this->database->SelectQueryEx('MEMB_INFO',                             // Table Name
            'memb___id,memb__pwd,cms_access',        // Select What
            'memb___id',Input::Post('login'));       // Where

        if($this->database->hasRows() == true)
        {
            while($RevData = $this->database->GetAsArray())
            {
                if((Input::Post('login') === $RevData['memb___id']) AND (Input::Post('password') === $RevData['memb___id']))
                {
                    session_regenerate_id(true);

                    $_SESSION['access']  = $RevData['cms_access'];
                    $_SESSION['account'] = $RevData['memb___id'];
                }
                else
                {
                    $valid[] = 'Account or Password is incorrect! Please try again...';
                }
            }
        }
        else
        {
            $valid[] = 'Account do not exist!';
        }

        return $valid;
    }

    public function Execute()
    {
        $this->template->prepareLanguage('block_login');
        return $this->loader->LoadLibrary('template')->writePanel($this->template->languageVariables['title'],$this->template->parseView('block_login'));
    }
} 