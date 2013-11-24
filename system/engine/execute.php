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

       // if(is_dir("data/validated/$current_page.valid.php"))
        {
            $PluginLoader = new PluginLoader();
            $Design = $PluginLoader->LoadPlugin('template',PLUGIN_TYPE_SYSTEM);
            //$Plugin = $PluginLoader->LoadPlugin($current_page,PLUGIN_TYPE_MODULES);

            $Design->Execute();

        }
      //  else
        {
            // Error plugin is not valid
        }
    }

} 