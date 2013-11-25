<?php
/**
 *  MuWebEx
 *
 *  Created by Kristians Jaunzems on 13.24.11.
 *  Copyright RealDev 2013. All rights reserved.
 *
 */

class Execute
{

    public static function Plugin()
    {
        $plugin_name    = Security::VerifyFileName(Input::Get('page'));
        $current_page   = $plugin_name == false ? 'home' : $plugin_name;

        $PluginLoader   = new PluginLoader();

        $Design         = $PluginLoader->LoadPlugin('template',PLUGIN_TYPE_SYSTEM);

        if(is_dir("data/validated/$current_page.valid.php"))
        {
            $Plugin         = $PluginLoader->LoadPlugin($current_page,PLUGIN_TYPE_MODULES);

            $accessLevels   = explode(',',(string)$Plugin->settings->access_level);

            if(in_array($_SESSION['access'],$accessLevels,true))
            {
                $Cache      = new Cache($current_page,(int)$Plugin->Settings->cache_refresh_time,true,false);

                if($_SESSION['database'] == true AND $_SERVER['REQUEST_METHOD'] === 'POST')
                {
                    if(Input::Post('plugins_post') !== false )
                    {

                    }
                    elseif(Input::Post('blocks_id') !== false AND Input::Post('blocks_post') !== false )
                    {

                    }
                }

                if($Plugin->Settings->cache_enabled == 'true' AND $Cache->CacheValid())
                {
                    $Cache->CacheLoad();
                }
                else
                {
                    if($Plugin instanceof IModule OR $Plugin instanceof IModuleMinEx)
                    {
                        $Plugin->Initialize();
                    }

                    $Design->template->addLocalVariable('content',$Plugin->Execute());

                    if($Plugin instanceof IModule)
                    {
                        $Plugin->Clear();
                    }
                }
            }
            else
            {
                $Design->template->addLocalVariable('content',$Plugin->Execute(''));
            }
        }
        else
        {
            $Design->Error_Module_Access();
        }

        echo $Design->Execute();
    }

} 