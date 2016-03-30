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
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    function store($array)
    {
        foreach($array as $key => $value)
        {
            $this->data[$key] = $value;
        }
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