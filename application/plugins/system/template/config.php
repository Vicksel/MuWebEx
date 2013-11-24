<?php
/**
 *  MuWebEx
 *
 *  Created by Kristians Jaunzems on 13.24.11.
 *  Copyright RealDev 2013. All rights reserved.
 *
 */


#############################################################################
#
#                          MAIN SETTINGS
#
#############################################################################
$config['module']               = 'template';       // Module name, should be the same as directory name.
$config['type']                 = 'system';         // Module type, mod_plugin,mod_hook,mod_block

$config['requiresPost']         = false;            // Module requires to post data

$config['requiresValidation']   = true;             // Enable automatic post data validation
$config['requiresDatabase']     = true;             // Enable SQL database support

$config['CostumSettings']       = true;

#############################################################################
#
#                          MODULE SETTINGS
#
#############################################################################

$config['Credits']              = array(
    'Author'    => 'Kristians Jaunzems',
    'About'     => 'Simple register module for MU CMS 2',
    'Email'     => 'kristins.jaunzems@inbox.lv',
);
