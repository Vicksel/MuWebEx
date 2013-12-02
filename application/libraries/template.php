<?php
/**
 *  MuWebEx
 *
 *  Created by Kristians Jaunzems on 13.25.11.
 *  Copyright RealDev 2013. All rights reserved.
 *
 */

class library_template
{

    public function writePanel($name,$content)
    {
        $msg = "<div class='panel panel-default'>
                    <div class='panel-heading'>$name</div>
                    <div class='panel-body'>
                    $content
                    </div>
                </div>";

        return $msg;
    }

    public function writePage($name,$content)
    {
        $msg = "<div class='panel panel-default'>
                    <div class='panel-heading'>$name</div>
                    <div class='panel-body'>
                    $content
                    </div>
                </div>";

        return $msg;
    }

    public function writeMessage($message,$type = 'warning')
    {
        $msg = '<div class="alert alert-'.$type.' ">
                  '.$message.'
                </div>';
        return $msg;
    }

}