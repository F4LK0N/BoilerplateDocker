<?php

### SOURCE ###

# ROOT
$temp = dirname(__DIR__);
while(false!==strpos($temp, '\\')){ $temp = str_replace('\\', '/', $temp); }
while(false!==strpos($temp, '//')){ $temp = str_replace('//', '/', $temp); }
$temp = rtrim($temp,"/");
define('PATH_ROOT', $temp);
unset($temp);

# FOLDERS
define('PATH_APP',     PATH_ROOT.'/app');
define('PATH_CORE',    PATH_ROOT.'/core');
define('PATH_PUBLIC',  PATH_ROOT.'/public');
define('PATH_TESTS',   PATH_ROOT.'/tests');



### HTML ###

# ROOT
$temp = $_SERVER["REQUEST_URI"];
while(false!==strpos($temp, '\\')){ $temp = str_replace('\\', '/', $temp); }
if(substr($temp, 0, 1)==='/'){ $temp=substr($temp, 1); }
$deep=substr_count($temp, '/');
$temp='';
while($deep>0){
    $temp.='../';
    $deep--;
}
define('HTML_ROOT', $temp);
unset($temp, $deep);

# RESOURCES
define('HTML_CSS', HTML_ROOT.'/css');
define('HTML_JS',  HTML_ROOT.'/js');
define('HTML_IMG', HTML_ROOT.'/img');
