<?php
/**
 *  MuWebEx
 *
 *  Created by Kristians Jaunzems on 13.24.11.
 *  Copyright RealDev 2013. All rights reserved.
 *
 */


/**
 * Class Cache
 */
class Cache
{
    /**
     * Contains full location and full file name for current cache file.
     *
     * @var string
     */
    private $CacheFile      = '';
    /**
     * Cache refresh time in minutes
     *
     * @var int|null
     */
    private $CacheRefresh   = NULL;
    /**
     * Enable basic cache file protection
     *
     * @var bool
     */
    private $Protection     = true;

    /**
     * @var bool
     */
    private $UserSpecified  = false;

    /**
     * @param string $Name          Cache file name
     * @param int    $ReloadTime    Cache reload time in minutes
     * @param bool   $Protection    Enable basic cache file protection
     * @param bool   $UserSpecified Enable user access lock
     */
    public function __construct($Name,$ReloadTime = 10,$Protection = true,$UserSpecified = false)
    {
        if($UserSpecified AND isset($_SESSION['account']))
        {
            $this->CacheFile     = "Cached/$Name.".$_SESSION['account'].'.'.$_SESSION['access'].'.'.$_SESSION['language'].".php";
        }
        else
        {
            $this->CacheFile    = "Cached/$Name.".$_SESSION['access'].'.'.$_SESSION['language'].".php";
        }

        $this->UserSpecified = $UserSpecified;
        $this->CacheRefresh  = ($ReloadTime * 60);
        $this->Protection    = $Protection;

        ob_start();
    }


    /**
     * Creates and writes to new or existing cache files.
     *
     * @param null $Content
     * @param bool $asVariable If enable all cache content will be writed to variable
     */
    public function CacheCreate($Content = NULL,$asVariable = false)
    {
        $Protection = ($this->Protection)       ?
            '<?php if(!defined("CACHE_ENABLED"))die("Direct access to cache is forbidden!"); ?>'.PHP_EOL : '';

        $Protection .= ($this->UserSpecified)   ?
            '<?php if(!isset($_SESSION["account"]) AND $_SESSION["account"] !== "'. $_SESSION['account'].'") { return ""; } ?>' : PHP_EOL;

        $toVar[] = $asVariable ?  '<?php $var='.'"'.PHP_EOL : '';
        $toVar[] = $asVariable ?  '"; ?>': '';

        $Content[] = ($Content == NULL) ? ob_get_contents() : $Content;

        $CacheFile = fopen($this->CacheFile, 'w');

        fwrite($CacheFile,$Protection. $toVar[0].$Content.$toVar[1]);
        fclose($CacheFile);

        ob_end_flush();
    }

    /**
     * Returns cache status, is it valid or expired.
     *
     * @return bool
     */
    public function CacheValid()
    {
        if(file_exists($this->CacheFile))
        {
            if(time() - $this->CacheRefresh  < filemtime($this->CacheFile))
            {
                return true;
            }
        }
        return false;
    }

    /**
     * Executes current active cache file or returns it's content.
     *
     * @param string $command
     * @return string
     */
    public function CacheLoad($command = 'include')
    {
        $token  = $_SESSION['token'];
        $var    = '';

        include $this->CacheFile;

        if($command == 'include')
        {
            echo $var;
        }
        else
        {
            return $var;
        }
    }
}