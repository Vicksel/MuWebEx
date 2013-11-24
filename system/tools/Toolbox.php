<?php
/**
 *  MuWebEx
 *
 *  Created by Kristians Jaunzems on 13.24.11.
 *  Copyright RealDev 2013. All rights reserved.
 *
 */

class Toolbox
{
    public static function writeLog($message,$prefix = NULL,$type= 'system',$includeTime = true)
    {
        $config = array(); // Define config array

        include 'application/config/log.php'; // Include log system config file

        if(!$config['log']['enabled']){ // Check if log system if active
            return;
        }

        if(in_array($type,$config['log']['standard_logs'])){ // Check if log categories is not custom

            // Check if log type configuration exists
            // in log categories list
            if(in_array($type,$config['log']['cat_status'])){
                // If log categories is disabled stop execution
                if(!$config['log']['cat_status'][$type]){
                    return;
                }
            }
            else{ // Log categories is not found in categories switch
                if(!$config['log']['cat_status']['default']) // If log categories switch default value is false stop execution
                    return;
            }

            if(!is_dir($config['log']['common_folder'])){    // Check if standard log directory exist
                mkdir($config['log']['common_folder']);      // If directory is not found the it will be created by php
            }
        }
        else
        {
            if($config['log']['log_custom_enabled'] == false){
                return;
            }

            if(!is_dir($config['log']['common_folder'])){   // Check if custom log directory exists
                if($config['log']['folder_auto_create']){   // Check if auto folder create is enabled
                    mkdir($config['log']['common_folder']); // If directory is not found the it will be created by php
                }else{
                    return;
                }
            }
        }

        $userName   = (isset($_SESSION['account']) ? $_SESSION['account'] : session_id());  // If user has logged in his account name will be used else session id code
        $logFile    = $config['log']['common_folder'].$config['log']['file_name'];          // Made log file location format: log/type/current_date.log
        $time       = ($includeTime) ? date('[d/M/Y H:i:s]') : '';                          // If enabled log write time will be added to message
        $prefix     = ($prefix == NULL) ? '' : "[$prefix]";                                 // If log have prefix it will be included if not nothing will be added

        file_put_contents($logFile ,"$time [".$userName."] $prefix $message" . PHP_EOL , FILE_APPEND); // Write prepared message to log file
    }
} 