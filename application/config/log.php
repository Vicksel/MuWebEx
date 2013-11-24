<?php

#############################################################################
#
#                               COMMON OPTIONS
#
#############################################################################

# With option you can enable or disable log system.
# Log system may slow down script on slow computers or if
# debugging logs are enabled, so please review witch categories you want
# to log bellow in Log Categories selection.
$config['log']['enabled']                   = true;
# With this option you can add support to dynamic log categories.
# If you script writes to non standard categories they will be automatically added
$config['log']['folder_auto_create']        = true;

#############################################################################
#
#                               LOG CATEGORIES
#
#############################################################################

# If option 'folder_auto_create' is enabled you can dynamically add new log categories
# and write to them, since there categories are creating dynamically you do not have control them
# but you can turn custom log categories on and off
$config['log']['log_custom_enabled']        = true;
# Default value for standard logs if their not found in categories switch list
$config['log']['cat_status']['default']     = true;

#############################################################################
#
#                           LOG CATEGORIES SWITCH
#
#############################################################################

$config['log']['cat_status']['sql-error']   = true;
$config['log']['cat_status']['system']      = true;

#############################################################################
#
#                               ADVANCED
#
#############################################################################

$config['log']['common_folder']         = "application/logs/$type/";
$config['log']['file_name']             = "".date('d_M_Y').".log";
$config['log']['standard_logs']         = array(
    'system',
    'security',
    'shop',
    'sql-message',
    'php-message',
    'php-error',
    'game-error',
    'sql-error'
);