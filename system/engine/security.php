<?php
/**
 *  MuWebEx
 *
 *  Created by Kristians Jaunzems on 13.24.11.
 *  Copyright RealDev 2013. All rights reserved.
 *
 */

class Security
{
    /**
     * Contains sensetive SQL keywords witch can lead to SQL injections.
     * All these keywords will be replaced by array left value.
     *
     * @var array
     */
    private static $SQLKeywordsTable = array(
                                                'select'    => '_slct_',
                                                'drop'      => '_drp_',
                                                'insert'    => '_insrt_',
                                                'or'        => '_o_r_',
                                                '#'         => '_sharp_',
                                                'shutdown'  => '_shtdwn_',
                                                'create'    => '_crte_',
                                                'delete'    => '_dlte_',
                                                'update'    => '_updte_',
                                                'where'     => '_whre_',
                                                'execute'   => '_excte_',
                                                'values'    => '_vles_',
                                                'use'       => '_use_'
                                            );
    /**
     * Contains sensitive javascript keywords can lead to XSS and other kinds of attacks
     * All these keywords will be replaced by array left value.
     *
     * @var array
     */
    private static $DisabledHTMLTags = array(
                                                'document.cookie'	=> '[removed]',
                                                'document.write'	=> '[removed]',
                                                '.parentNode'		=> '[removed]',
                                                '.innerHTML'		=> '[removed]',
                                                'window.location'	=> '[removed]',
                                                '-moz-binding'		=> '[removed]',
                                                '<!--'				=> '&lt;!--',
                                                '-->'				=> '--&gt;',
                                                '<![CDATA['			=> '&lt;![CDATA[',
                                                '<comment>'			=> '&lt;comment&gt;'
                                            );

    /**
     * Contains sensitive javascript functions can lead to XSS and other kinds of attacks
     * All these keywords will be replaced by array left value.
     *
     * @var array
     */
    private static $DisabledHTMLTagRegex = array(
                                                'javascript\s*:',
                                                'expression\s*(\(|&\#40;)',
                                                'vbscript\s*:',
                                                'Redirect\s+302',
                                                "([\"'])?data\s*:[^\\1]*?base64[^\\1]*?,[^\\1]*?\\1?"
                                            );

    /**
     * Contains sensitive php functions can lead to XSS and other kinds of attacks
     * All these keywords be translated as plain text
     *
     * @var string
     */
    private static $DisabledPHPFunctions = 'alert|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink';

    /**
     * Secures selected string or array.
     *
     * @param  string $string
     * @return array|mixed
     */
    public static function CleanXSS($string)
    {
        if (is_array($string))
        {
            $arr = array();
            foreach($string as $array => $value)
            {
                $value = self::RemoveInvisibleCharacters($value);
                $value = self::ConvertTabsToSpaces($value);
                $value = self::RemoveHTMLInjections($value);
                $value = self::RemoveQuotes($value);
                $value = self::DisableBadPHPFunctions($value);

                $arr[$array] = $value;
            }
            return $arr;
        }

        $originalString = $string;

        $string = self::RemoveInvisibleCharacters($string);
        $string = self::ConvertTabsToSpaces($string);
        $string = self::RemoveHTMLInjections($string);
        $string = self::RemoveQuotes($string);
        $string = self::DisableBadPHPFunctions($string);

        return $string;
    }

    /**
     * Removed non visible characters
     *
     * @param $string
     * @return mixed
     */
    private static function RemoveInvisibleCharacters($string)
    {
        $non_displayables = array();

        $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';

        do
        {
            $string = preg_replace($non_displayables, '', $string, -1, $count);
        }
        while ($count);

        return $string;
    }

    /**
     * Converts tabs into spaces
     *
     * @param $string
     * @return mixed
     */
    private static function ConvertTabsToSpaces($string)
    {
        if (strpos($string, "\t") !== FALSE)
        {
            $string = str_replace("\t", ' ', $string);
        }
        return $string;
    }

    /**
     * Removes all html based attacks
     *
     * @param $string
     * @return mixed
     */
    private static function RemoveHTMLInjections($string)
    {
        $string = str_replace(array_keys(self::$DisabledHTMLTags), self::$DisabledHTMLTags, $string);

        foreach (self::$DisabledHTMLTagRegex as $regex)
        {
            $string = preg_replace('#'.$regex.'#is', '[removed]', $string);
        }

        return $string;
    }

    /**
     * Renders php function into plain text
     *
     * @param $string
     * @return mixed
     */
    private static function DisableBadPHPFunctions($string)
    {
        $string = preg_replace('#('.self::$DisabledPHPFunctions.')(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $string);

        return $string;
    }

    /**
     * Converts sensitive SQL characters
     *
     * @param $string
     * @param bool $convertBack
     * @return mixed
     */
    public static function SecureSQLInputConvert($string,$convertBack = false)
    {
        foreach (self::$SQLKeywordsTable as $SQLVar => $VarNew)
        {
            if($convertBack)
            {
                $string = preg_replace($SQLVar,$VarNew,$string);
            }
            else
            {
                $string = preg_replace($VarNew,$SQLVar,$string);
            }
        }

        return $string;
    }

    /**
     * Removed quotes from string
     *
     * @param $string
     * @return string
     */
    private static function RemoveQuotes($string)
    {
        $string = str_replace("'","",$string);
        $string = str_replace('"',"",$string);
        $string = str_replace('--',"",$string);

        return $string;
    }

    /**
     * Secures folder and file name
     *
     * @param $string
     * @return string
     */
    public static function VerifyFileName($string)
    {
        $bad = array(
            "../",
            "<!--",
            "-->",
            "<",
            ">",
            "'",
            '"',
            '&',
            '$',
            '#',
            '{',
            '}',
            '[',
            ']',
            '=',
            ';',
            '?',
            "%20",
            "%22",
            "%3c",		// <
            "%253c",	// <
            "%3e",		// >
            "%0e",		// >
            "%28",		// (
            "%29",		// )
            "%2528",	// (
            "%26",		// &
            "%24",		// $
            "%3f",		// ?
            "%3b",		// ;
            "%3d"		// =
        );
        return stripslashes(str_replace($bad, '', $string));
    }
}