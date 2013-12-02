<?php if(!defined("CACHE_ENABLED"))die("Direct access to cache is forbidden!"); ?>
<?php
$CacheVar = "<div class='panel panel-default'>
                    <div class='panel-heading'>Login</div>
                    <div class='panel-body'>
                    <form accept-charset='UTF-8' role='form' method='post' action='?token=$params[token]'> <fieldset> <div class='form-group'> <div class='input-group'> <span class='input-group-addon'><span class='glyphicon glyphicon-user'></span></span> <input type='text' class='form-control' name='form_username' placeholder='Username' /> </div> </div> <div class='form-group'> <div class='input-group'> <span class='input-group-addon'><span class='glyphicon glyphicon-lock'></span></span> <input type='password' class='form-control' placeholder='Password' /> </div> </div> <input type='hidden' name='plugin_submit' value='login'> <input class='btn btn-lg btn-success btn-block' type='submit' value='Login'> </fieldset> </form>
                    </div>
                </div>";
?>