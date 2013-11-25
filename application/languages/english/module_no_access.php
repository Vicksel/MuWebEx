<?php
/**
 *  MuWebEx
 *
 *  Created by Kristians Jaunzems on 13.25.11.
 *  Copyright RealDev 2013. All rights reserved.
 *
 */

$language['title']                  = 'No Permission!';

$language['intro']                  = 'Hello, user, the thing is we do not like you, so we have disabled your access to this page!';
$language['reason_title']           = "Why i'm seeing this message?";

$language['reason_list']            = array();

$language['reason_list'][]['msg']   = 'You do not have required permission to access this page!';
$language['reason_list'][]['msg']   = 'You are trying to do some not permitted stuff! Good luck with that!';

$language['reason_conclusion']      = 'Your current access level is '.$_SESSION['access'].', but this page requires access level {required_access}!';