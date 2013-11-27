<?php
/**
 *  MuWebEx
 *
 *  Created by Kristians Jaunzems on 13.24.11.
 *  Copyright RealDev 2013. All rights reserved.
 *
 */

class Template {
    /**
     * Contains all global scope variables that can be accessed from all template files.
     *
     * @var array
     */
    private static $globalVariables = array();
    /**
     * Contains current template specified variables, that can be accessed only from current template.
     *
     * @var array
     */
    private $localVariables = array();
    /**
     * Holds all language related variables
     *
     * @var array
     */
    public $languageVariables = array();
    /**
     * Current view file location.
     *
     * @var null
     */
    private $mergedVariables = array();
    /**
     * Current view file location.
     *
     * @var null
     */
    private $viewFile = NULL;
    /**
     * Current view file content.
     *
     * @var string
     */
    private $viewContent = "";
    /**
     * Setting up default values for current template
     *
     * @param null $viewFile
     */
    public function __construct($viewFile = NULL)
    {
        $this->viewFile     = $viewFile;
    }

    /**
     * Adds a local scope variable into array.
     *
     * @param $id
     * @param $value
     * @throws Exception
     */
    public function addLocalVariable($id,$value)
    {
        if(array_key_exists($id,$this->localVariables))
        {
                throw new Exception("Selected key '$id' already exist in array 'localVariables'");
        }

        if(!isset($id))
        {
            throw new Exception('Please recheck if $id,$value is set correctly!');
        }

        $this->localVariables[$id] = $value;
    }

    /**
     * @param $key
     * @param $array
     */
    public function updateArrayVariable($key,$array)
    {
        if(is_array($this->localVariables[$key]))
        {
            $this->localVariables[$key] = array_merge($this->localVariables[$key],$array);
        }
    }

    /**
     * @param $key
     * @param $add
     */
    public function updateVariableValue($key,$add)
    {
        if(isset($this->localVariables[$key]))
        {
            $this->localVariables[$key] .= $add;
        }
    }

    /**
     * Adds a global scope variable into array.
     *
     * @param $id
     * @param $value
     * @throws Exception
     */
    public static function addGlobalVariable($id,$value)
    {
        if(array_key_exists($id,self::$globalVariables))
        {
            throw new Exception("Selected key '$id' already exist in array 'self::globalVariables'");
        }

        if(!isset($id) OR !isset($id))
        {
            throw new Exception('Please recheck if $id,$value is set correctly!');
        }

        self::$globalVariables[$id] = $value;
    }

    /**
     * @param $key
     * @return bool
     */
    public function KeyIsset($key)
    {
        if(isset($this->localVariables[$key]))
        {
            return true;
        }
        return false;
    }


    /**
     * Loads language file variables into language variable
     * If new language array have same keys as existing language they
     * will be replaced by newer language array keys
     *
     * @param string $file
     * @param string $defaultLanguage
     * @param bool   $overwriteUser
     * @param bool   $mergeWithCurrent
     */
    public function prepareLanguage($file,$defaultLanguage = '',$overwriteUser = false,$mergeWithCurrent = false)
    {
        $language = array();

        if(is_file("application/languages/".$_SESSION['language']."/$file.php") AND $overwriteUser == false)
        {
            include "application/languages/".$_SESSION['language']."/$file.php";
        }
        elseif($defaultLanguage !== '' AND is_file("application/languages/".$_SESSION['language']."/$file.php"))
        {
            include "application/languages/".$defaultLanguage."/$file.php";
        }

        if($mergeWithCurrent)
        {
            $this->languageVariables = array_merge($this->languageVariables,$language);
        }
        else
        {
            $this->languageVariables = $language;
        }
    }

    /**
     * Parses a view file and outputs generated content.
     *
     * @param   null $viewFile
     * @param   bool $compact
     * @throws  Exception
     * @returns string template parsed content
     */
    public function parseView($viewFile = NULL,$compact = true,$reparse = false)
    {
        if($viewFile != NULL)
        {
            $this->viewFile = $viewFile;
        }
        else
        {
            if($viewFile == NULL)
            {
                throw new Exception('Template view file cannot be empty');
            }
        }

        $this->viewFile = 'application/views/'.$this->viewFile.'.html';

        if(!is_file($this->viewFile))
        {
            throw new Exception('You must point to a valid view file!');
        }

        $this->viewContent      = file_get_contents($this->viewFile);
        $this->mergedVariables  = array_merge(self::$globalVariables,$this->localVariables,$this->languageVariables);

        if(preg_match_all("/{{([^\/}]+)}}(.*){{\/\\1}}/ms", $this->viewContent, $matches) !== false)
        {
            $loopStrings    = $matches[0];
            $loopVariables  = $matches[1];
            $loopBlocks     = $matches[2];

            for($i = 0; $i < count($loopVariables); $i++)
            {
                if(isset($this->mergedVariables[$loopVariables[$i]]) && is_array($this->mergedVariables[$loopVariables[$i]]))
                {
                    $preparedString = "";

                    for($j = 0; $j < count($this->mergedVariables[$loopVariables[$i]]); $j++)
                    {
                        $block = $loopBlocks[$i];
                        foreach($this->mergedVariables[$loopVariables[$i]][$j] as $key => $value)
                        {
                            $block = str_replace("{{" . $key . "}}", $value, $block);
                        }
                        $preparedString .= $block;
                    }
                    $this->viewContent = str_replace($loopStrings[$i], $preparedString, $this->viewContent);
                }
                else
                {
                    $this->viewContent = str_replace($loopStrings[$i], "", $this->viewContent);
                }
            }
        }

        $this->viewContent = preg_replace("/{{[^}]+}}/", "", $this->viewContent);

        if(count($this->mergedVariables) > 0)
        {
            foreach($this->mergedVariables as $key => $value)
            {
                if(!is_array($value))
                {
                    $this->viewContent = str_replace("{".$key."}",$value,$this->viewContent);
                }
            }
            if($reparse)
            {
                foreach($this->mergedVariables as $key => $value)
                {
                    if(!is_array($value))
                    {
                        $this->viewContent = str_replace("{".$key."}",$value,$this->viewContent);
                    }
                }
            }
        }

        if($compact == true)
        {
            $this->viewContent = preg_replace("/\n|\t|\r/", " ", $this->viewContent);
            $this->viewContent = preg_replace("/ +/", " ", $this->viewContent);
        }

        return $this->viewContent;
    }
} 