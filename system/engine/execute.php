<?php
/**
 *  MuWebEx
 *
 *  Created by Kristians Jaunzems on 13.24.11.
 *  Copyright RealDev 2013. All rights reserved.
 *
 */

if(!defined(WEB_ENGINE))
    die('Direct access to system modules is forbidden!');

define('CACHE_ENABLED', 'CACHE_ENABLED');
define('MODULE_EXECUTE','MODULE_EXECUTE');

class Execute
{
    public static function Plugin()
    {
        $plugin_name    = Security::VerifyFileName(Input::Get('page'));
        $current_page   = $plugin_name == false ? 'home' : $plugin_name;

        $PluginLoader   = new Loader();

        $Design         = $PluginLoader->LoadPlugin('template',PLUGIN_TYPE_SYSTEM);

        if(Toolbox::userExecutablePlugin($current_page) AND Toolbox::isPluginValid($current_page))
        {
            $Plugin         = $PluginLoader->LoadPlugin($current_page,PLUGIN_TYPE_MODULES);

            $accessLevels   = explode(',',(string)$Plugin->settings->access_level);

            if(in_array($_SESSION['access'],$accessLevels,true))
            {
                $Cache      = new Cache($current_page,$Plugin->settings->cache_refresh_time,false);

                /**
                 * Automatic Post Validation
                 */
                $ErrorList  = '';

                if($_SERVER['REQUEST_METHOD'] === 'POST' AND Input::Post('plugin_submit') !== false)
                {
                    if($_SESSION['database'] === true)
                    {
                        $plugin_send = Input::Post('plugin_submit');

                        /*
                         * We must check if sender form plugin is valid.
                         *
                         * We check if it is user executable(Block & Module type)
                         * and if selected plugin is active on this system
                         *
                         */
                        if(Toolbox::userExecutablePlugin($plugin_send) AND Toolbox::isPluginValid($plugin_send))
                        {
                            /*
                             * If module have been passed initial check, we check if it is
                             * already loaded on this system, if it's not it maybe injection attempt
                             *
                             */

                            //if($PluginLoader->isLoaded($plugin_send,Toolbox::getPluginType($plugin_send)))
                            {
                                if($_SESSION['token'] === Input::Get('token'))
                                {
                                    $pluginEx   = $PluginLoader->LoadPlugin($plugin_send,Toolbox::getPluginType($plugin_send));
                                    $Passed     = true;

                                    if($pluginEx->config['requiresValidation'] == true)
                                    {
                                        $AutoValidation     = NULL;
                                        $PluginValidation   = NULL;

                                        if($pluginEx instanceof IModule OR $pluginEx instanceof IModuleMed OR $pluginEx instanceof IModuleMedEx)
                                        {
                                            $PluginValidation = $pluginEx->ValidateData();
                                        }

                                        $AutoValidation = Validate::ValidateArray($pluginEx->config['Validation']);

                                        if(is_array($AutoValidation))
                                        {
                                            foreach($AutoValidation as $error)
                                            {
                                                $ErrorList .= Loader::LoadLibraryEx('template')->writeMessage($error,'danger');
                                            }
                                            $Passed = false;
                                        }
                                        if(is_array($PluginValidation))
                                        {
                                            foreach($PluginValidation as $error)
                                            {
                                                $ErrorList .= $PluginLoader->LoadLibrary('template')->writeMessage($error,'danger');
                                            }
                                            $Passed = false;
                                        }
                                    }

                                    if($Passed == true)
                                    {
                                        if($pluginEx instanceof IModule OR $pluginEx instanceof IModuleMed)
                                        {
                                            $Design->template->addLocalVariable('post_response',$Plugin->SendData());
                                        }
                                    }

                                    $_SESSION['token'] = md5(uniqid(rand(), true)); // Generate new Token
                                }
                                else
                                {
                                    $ErrorList .= $PluginLoader->LoadLibrary('template')->writeMessage('Post token is not valid!'.$_SESSION['token'],'danger');

                                }
                            }
                            //else
                            {
                                //$ErrorList .= $PluginLoader->LoadLibrary('template')->writeMessage('Post request is not valid! Plugin is not active','danger');
                            }
                        }
                        else
                        {
                            $ErrorList .= $PluginLoader->LoadLibrary('template')->writeMessage('Post request is not valid!','danger');
                        }
                    }
                    else
                    {
                        $ErrorList .= $PluginLoader->LoadLibrary('template')->writeMessage('Could not connect database!','danger');
                    }
                }

                $Design->template->addLocalVariable('errors',$ErrorList);

                /** End of Automatic Post Validation */

                /**
                 * Cache Loader
                 */

                if($Plugin->settings->cache_enabled == 'true' AND $Cache->CacheValid())
                {
                    $Cache->CacheLoad();
                }

                else
                {
                    if($Plugin instanceof IModule OR $Plugin instanceof IModuleMinEx)
                    {
                        $Plugin->Initialize();
                    }

                    $Design->template->addLocalVariable('menu',         self::GenerateMenu());
                    $Design->template->addLocalVariable('blocks',       self::GenerateBlocks());
                    $Design->template->addLocalVariable('content',      $Plugin->Execute());
                    $Design->template->addLocalVariable('module_title', $Plugin->template->languageVariables['title']);


                    if($Plugin instanceof IModule)
                    {
                        $Plugin->Clear();
                    }

                    if($Plugin->settings->cache_enabled == 'true' AND $Cache->CacheValid())
                    {
                        $Cache->CacheCreate(NULL,true);
                    }
                }
            }
            else
            {
                $Design->Error_Module_Access();
            }
        }
        else
        {
            $Design->Error_Module_Valid();
        }

        echo $Design->Execute();
    }

    public static function GenerateMenu()
    {
        $Cache = new Cache('site_menu',5);

        if($Cache->CacheValid())
        {
            return json_decode(str_replace("'",'"',$Cache->CacheLoad(NULL)));
        }
        else
        {
            $Menu               = array();
            $AvailablePlugins   = scandir('application/plugins/modules/');

            foreach($AvailablePlugins as $plugin)
            {
                if(Toolbox::isPluginValid($plugin))
                {
                    $language = array();

                    require 'application/languages/'.$_SESSION['language'].'/'.$plugin.'.php';

                    $plugin_        = simplexml_load_file('application/plugins/modules/'.$plugin.'/settings.xml');
                    $accessLevels   = explode(',',(string)$plugin_->access_level);

                    if(in_array($_SESSION['access'],$accessLevels,true))
                    {
                        $Menu[] = array(
                            'name' => $language['title'],
                            'link' => '?page='.$plugin,
                        );
                    }

                    $Cache->CacheCreate(str_replace('"',"'",json_encode($Menu)),true);
                }
            }
            return $Menu;
        }
    }

    public static function GenerateBlocks()
    {
        $Block              = '';
        $AvailablePlugins   = scandir('application/plugins/blocks/');

        foreach($AvailablePlugins as $plugin)
        {
            if(Toolbox::isPluginValid($plugin))
            {
                $Loader = new Loader();

                $pluginEx = $Loader->LoadPlugin($plugin,PLUGIN_TYPE_BLOCKS);

                $Cache = new Cache('block_'.$plugin,$pluginEx->settings->cache_refresh_time,true,$pluginEx->config['CachePersonal']);

                if($Cache->CacheValid() AND $pluginEx->settings->cache_enabled == 'true')
                {
                    $Block .= $Cache->CacheLoad(array('token' => $_SESSION['token']));
                }
                else
                {
                    $accessLevels   = explode(',',(string)$pluginEx->settings->access_level);

                    if(in_array($_SESSION['access'],$accessLevels,true))
                    {
                        $Block .= $pluginEx->Execute();

                        if(!$Cache->CacheValid() AND $pluginEx->settings->cache_enabled == 'true')
                        {
                            $Cache->CacheCreate($Block,true);
                        }
                    }
                }
            }
        }
        return $Block;
    }
} 