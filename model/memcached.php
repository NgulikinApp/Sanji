<?php
    $cache = new Memcached();
    $cache->addServer('ngulikin.com', 0);
    
    /*
        Function referred on : all
        Used for checking the data memcache is exist or not
        Return data : true or false
    */
    function checkMemcached($key,$cache){
        return $cache->get($key);
    }
    
    /*
        Function referred on : all
        Used for getting the data memcache
        Return data : tstring
    */
    function writeMemcached($key,$cache){
        /*
            Function location in : generatejson.php
        */
        generateJSON($cache->get($key));
    }
?>