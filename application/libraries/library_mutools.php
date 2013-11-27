<?php
/**
 *  MuWebEx
 *
 *  Created by Kristians Jaunzems on 13.27.11.
 *  Copyright RealDev 2013. All rights reserved.
 *
 */

class library_mutools
{
    /**
     * Gets guild image generated from source
     *
     * @param     $source       guild logo hex code
     * @param     $name         guild name
     * @param int $size         generated image size
     *
     * @return string
     */
    static function getGuildLogo($source,$name,$size=64)
    {
        $hex		= urlencode(bin2hex($source));
        $pixelSize	= $size / 8;
        $img 		= ImageCreate($size,$size);

        if(is_file("application/cache/guild.$name.$size.png"))
        {
            $output	= "<img alt='$name' src='application/cache/guild.$name.$size.png'>";
        }
        else
        {
            if(@preg_match('/[^a-zA-Z0-9]/',$hex) || $hex == '')
            {
                $hex	= '0044450004445550441551554515515655555566551551660551166000566600';
            }
            else
            {
                $hex	= stripslashes($hex);
            }
            for ($y = 0; $y < 8; $y++)
            {
                for ($x = 0; $x < 8; $x++)
                {
                    $offset	= ($y*8)+$x;
                    if		(substr($hex, $offset, 1) == '0')	{$c1 = "";		$c2 = ""; 		$c3 = "";		}
                    elseif	(substr($hex, $offset, 1) == '1')	{$c1 = "0";		$c2 = "0"; 		$c3 = "0";		}
                    elseif	(substr($hex, $offset, 1) == '2')	{$c1 = "128"; 	$c2 = "128"; 	$c3 = "128";	}
                    elseif	(substr($hex, $offset, 1) == '3')	{$c1 = "255"; 	$c2 = "255"; 	$c3 = "255";	}
                    elseif	(substr($hex, $offset, 1) == '4')	{$c1 = "255"; 	$c2 = "0"; 		$c3 = "0";		}
                    elseif	(substr($hex, $offset, 1) == '5')	{$c1 = "255"; 	$c2 = "128"; 	$c3 = "0";		}
                    elseif	(substr($hex, $offset, 1) == '6')	{$c1 = "255"; 	$c2 = "255"; 	$c3 = "0";		}
                    elseif	(substr($hex, $offset, 1) == '7')	{$c1 = "128"; 	$c2 = "255"; 	$c3 = "0";		}
                    elseif	(substr($hex, $offset, 1) == '8')	{$c1 = "0"; 	$c2 = "255"; 	$c3 = "0";		}
                    elseif	(substr($hex, $offset, 1) == '9')	{$c1 = "0"; 	$c2 = "255"; 	$c3 = "128";	}
                    elseif	(substr($hex, $offset, 1) == 'a')	{$c1 = "0"; 	$c2 = "255";	$c3 = "255";	}
                    elseif	(substr($hex, $offset, 1) == 'b')	{$c1 = "0"; 	$c2 = "128"; 	$c3 = "255";	}
                    elseif	(substr($hex, $offset, 1) == 'c')	{$c1 = "0"; 	$c2 = "0"; 		$c3 = "255";	}
                    elseif	(substr($hex, $offset, 1) == 'd')	{$c1 = "128"; 	$c2 = "0"; 		$c3 = "255";	}
                    elseif	(substr($hex, $offset, 1) == 'e')	{$c1 = "255"; 	$c2 = "0"; 		$c3 = "255";	}
                    elseif	(substr($hex, $offset, 1) == 'f')	{$c1 = "255"; 	$c2 = "0"; 		$c3 = "128";	}
                    else										{$c1 = "255"; 	$c2 = "255"; 	$c3 = "255";	}
                    $row[$x] 		= $x*$pixelSize;
                    $row[$y] 		= $y*$pixelSize;
                    $row2[$x] 		= $row[$x] + $pixelSize;
                    $row2[$y]		= $row[$y] + $pixelSize;
                    $color[$y][$x]	= imagecolorallocate($img, $c1, $c2, $c3);
                    imagefilledrectangle($img, $row[$x], $row[$y], $row2[$x], $row2[$y], $color[$y][$x]);
                }
            }
            Imagepng($img,"application/cache/guild.$name.$size.png");Imagedestroy($img);

            $output	= "<img alt='' src='application/cache/guild.$name.$size.png'>";
        }
        return $output;
    }
    /**
     * Formats zen output
     *
     * @param int $money        amount of character money
     *
     * @return string
     */
    static function formatMoneyOutput($money)
    {
        $format	= array("","k","kk","kkk","kkkk","kkkkk");
        $c		= 0;

        while ($money >= 1000)
        {
            $c++;
            $money	= $money / 1000;
        }

        $money	= number_format($money,($c ? 2 : 0),",",".")." ".$format[$c];

        return $money;
    }
    /**
     * Get class name or code
     *
     * @param int $code
     *
     * @return int
     */
    static function getClass($code)
    {
        $class	= array
        (
            '0'		=>	'Dark Wizard',
            '1'		=>	'Soul Master',
            '2'		=>	'Grand Master',
            '16'	=>	'Dark Knight',
            '17'	=>	'Blade Knight',
            '18'	=>	'Blade Master',
            '32'	=>	'Fairy Elf',
            '33'	=>	'Muse Elf',
            '34'	=>	'High Elf',
            '48'	=>	'Magic Gladiator',
            '49'	=>	'Duel Master',
            '64'	=>	'Dark Lord',
            '65'	=>	'Lord Emperor',
            '80'	=>	'Summoner',
            '81'	=>	'Bloody Summoner',
            '82'	=>	'Dimension Master',
            '96'	=>	'Rage Fighter',
            '97'	=>	'Fist Master',
        );

        return $class[(string)$code];
    }
} 