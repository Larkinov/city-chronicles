<?php 

spl_autoload_register(function(string $name){
    $entity = str_replace('\\',DIRECTORY_SEPARATOR,$name);
    $entity = str_replace('vkbot_conversation','/vendor',$entity);
    $entity = str_replace('CityChronicles','',$entity);
    $path = __DIR__."/src"."{$entity}.php";

    if(is_readable($path)){
        
        require($path);
    }
});

?>