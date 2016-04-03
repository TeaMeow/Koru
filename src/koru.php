<?php
class Koru
{
    static function build($data)
    {
        return new KoruData($data);
    }
}

class KoruData
{
    private $data       = [];
    private $isTroubled = false;

    function __construct($data)
    {
        foreach($data as $key => $value)
            $this->data[$key] = $value;
    }

    function __get($name)
    {
        return $this->get($name);
    }

    function __set($name, $value)
    {
        return $this->set($name, $value);
    }

    function jsonDecode($name)
    {
        if(isset($this->data[$name]))
            $this->set($name, json_decode($this->data[$name]));
            
        return $this;
    }

    function store($array)
    {
        foreach($array as $key => $value)
            $this->set($key, $value);
        
        return $this;
    }
    
    function get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }
    
    function set($name, $value)
    {
        $this->data[$name] = $value;
        
        return $this;
    }
    
    function isEmpty($name)
    {
        return empty($this->get($name));
    }
    
    function isNull($name)
    {
        return is_null($this->get($name));
    }
    
    function isTroubled($set = false)
    {
        if($set)
            $this->isTroubled = true;
        else
            return $this->isTroubled;

        return $this;
    }

    function output()
    {
        return $this->data;
    }
}
?>