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
#                           Windows Authentication
#
#############################################################################

# Use windows authentication only if you are hosting on
# same server as SQL server. Using windows auth you
$WindowsAuth                 = true;

#############################################################################
#
#                                SQL Address
#
#############################################################################

# SQL server connection address. This must be changed only
# if you are connecting to sql witch is not hosted on same server
# as mu online server.
$ServerHost                  = '(local)';

#############################################################################
#
#                              Database Options
#
#############################################################################

# Mu Server database where accounts all information are stored
$database['Database']        = 'MuOnline';

if($WindowsAuth == false)
{
    $database['Login']       = 'sa';
    $database['Password']    = 'gaujas37';
}
