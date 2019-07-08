<?php
    $cache = new Memcached();
    $cache->addServer('localhost', 11211) or die ("Unable to connect");
    
    /*
        Function referred on : all
        Used for getting the data memcache
        Return data : string
    */
    function getMemcached($key,$cache){
        /*
            Function location in : generatejson.php
        */
        return $cache->get($key);
    }
    
    function setMemcached($key,$cache,$data,$exp){
        $cache->set($key, $data, $exp);
    }
?>