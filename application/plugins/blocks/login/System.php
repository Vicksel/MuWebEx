<?php
$config = array();

#############################################################################
#
#                          MAIN SETTINGS
#
#############################################################################
$config['requiresValidation']   = true;
$config['requiresDatabase']     = true;             // Enable MSSQL database support
$config['requiresSQLite']       = true;             // Enable SQLite database support
$config['CostumSettings']       = true;
$config['CachePersonal']        = false;

#############################################################################
#
#                          MODULE SETTINGS
#
#############################################################################
$config['Validation']           = array(
                                    'form_username' => array(
                                        'Name'          => 'Username',
                                        'Type'          => 'string',
                                        'AllowSpaces'   => false,
                                        'Length'        => array(90,10),
                                        'Required'      => true,
                                    ),
                                );
$config['Credits']              = array(
                                    'Author'    => 'Kristians Jaunzems',
                                    'About'     => 'Simple register module for MU CMS 2',
                                    'Email'     => 'kristins.jaunzems@inbox.lv',
                                  );


