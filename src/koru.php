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
    function __construct($data)
    {
        foreach($data as $key => $value)
            $this->$key = $value;
    }
    
    function __get($name)
    {
        return property_exists($this, $name) ? $this->$name : null;
    }
    
    //function toJson($options = null)
    //{
    //    return json_encode(get_object_vars($this), func_get_args());
    //}
    
    function add($key, $value)
    {
        $this->$key = $value;
        
        return $this;
    }
    
    function remove($key)
    {
        if(isset($this->$key))
            unset($this->$key);
            
        return $this;
    }
    
    function output()
    {
        return get_object_vars($this);
    }
}
?>