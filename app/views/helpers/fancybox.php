<?php
class FancyboxHelper extends AppHelper {

  function getUrlLightbox($filename){

    $extension = end(explode('.', $filename));
    $tmp_file = sys_get_temp_dir().'/'.substr($filename, strrpos($filename , '/')+1);
    
    if(strpos($tmp_file, "\\"))
    	$tmp_file = str_replace('/', '\\', $tmp_file);

    copy($filename, $tmp_file);

    return $tmp_file;

  }
}?>