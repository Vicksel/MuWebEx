<?php
/**
 *  MuWebEx
 *
 *  Created by Kristians Jaunzems on 13.25.11.
 *  Copyright RealDev 2013. All rights reserved.
 *
 */

$config['Validation']           = array(
    'form_login' => array(
        'Name'          => 'Username',
        'Type'          => 'string',
        'AllowSpaces'   => false,
        'Length'        => array(4,10),
        'Required'      => true,
    ),
);
class Validate
{
    public static function Validate($ControlArray)
    {
        if(count($ControlArray) > 0)
        {
            foreach($ControlArray as $field => $value)
            {
                switch($value['Type'])
                {
                    case 'alpha':
                        if(!ctype_alpha(Input::Post($field)))
                        {
                            $error[] = $value['Name'].' is not valid, this field must contain only alphabetic characters (AZaz)';
                        }
                    break;
                    case 'alphanum':
                        if(!ctype_alpha(Input::Post($field)))
                        {
                            $error[] = $value['Name'].' is not valid, this field must contain only alphanumeric characters (AZaz0-9)';
                        }
                        break;
                    case 'digit':
                        Input::OverwritePost($field,(string)Input::Post($field)); // http://us3.php.net/manual/en/function.ctype-digit.php

                        if(!ctype_digit(Input::Post($field)))
                        {
                            $error[] = $value['Name'].' is not valid, this field must contain only digits (0-9)';
                        }
                    break;
                    case 'mail':
                        if (!filter_var(Input::Post($field), FILTER_VALIDATE_EMAIL)) {
                            $error[] = $value['Name'].' is not valid email address, please recheck and try again';
                        }
                    break;
                }

                if($value['Required'] == true AND Input::Post($field) == false){
                    $error[] = $value['Name'].' is not set, please set this field and try again!';
                }

                if($value['Type'] != 'digit')
                {
                    if(strlen(Input::Post($field)) < $value['Length'][0] OR strlen(Input::Post($field)) > $value['Length'][1])
                    {
                        $error[] = $value['Name'].' minimal length is '.$value['Length'][0].' and maximal length is '.$value['Length'][1].' characters, Your length:'.strlen(Input::Post($field)).'!';
                    }
                }

                if($value['AllowSpaces'] == false AND preg_match('/\s/',Input::Post($field)))
                {
                    $error[] = $value['Name'].' contains spaces. Please remove them!';
                }
            }
        }
    }
} 