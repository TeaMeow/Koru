<?php
class Koru
{
    /**
     * Build
     *
     * @param array $data
     */

    static function build($data = null)
    {
        $data = $data ?: [];

        return new KoruData($data);
    }
}





class KoruData
{
    /**
     * Data
     *
     * Stores the main data here.
     *
     * @var array
     */

    private $data       = [];

    /**
     * Is Troubled
     *
     * Set true when the data invalid or has some problems.
     *
     * @var bool
     */

    private $isTroubled = false;




    /**
     * CONSTRUCT
     */

    function __construct($data)
    {
        foreach($data as $key => $value)
            $this->data[$key] = $value;
    }




    /**
     * GET
     *
     * @return mixed
     */

    function __get($name)
    {
        return $this->get($name);
    }




    /**
     * SET
     *
     * @return KoruData
     */

    function __set($name, $value)
    {
        return $this->set($name, $value);
    }




    /**
     * Json Decode
     *
     * Decode a json data and store to the same place.
     *
     * @param string $name   The namd of the data.
     *
     * @return KoruData
     */

    function jsonDecode($name)
    {
        if(isset($this->data[$name]))
            $this->set($name, json_decode($this->data[$name]));

        return $this;
    }




    /**
     * Clean
     *
     * Specify some datas to clean.
     *
     * @param string $variables   The name of the data to clean, seperate with commas (ex: a, b, c).
     *
     * @return KoruData
     */

    function clean($variables)
    {
        $variables = explode(', ', $variables);

        foreach($this->data as $key => $value)
        {
            foreach($variables as $variable)
                if($key === $variable)
                    unset($this->data[$key]);
        }

        return $this;
    }




    /**
     * Leave
     *
     * Clean all the datas expect something.
     *
     * @param string $variables   The name of the data to keep, seperate with commas (ex: a, b, c).
     *
     * @return KoruData
     */

    function leave($variables)
    {
        $variables = explode(', ', $variables);

        foreach($this->data as $key => $value)
        {
            $expect = false;

            foreach($variables as $variable)
                if($key === $variable)
                    $expect = true;

            if(!$expect)
                unset($this->data[$key]);
        }

        return $this;
    }




    /**
     * Store
     *
     * Store many datas in the same time.
     *
     * @param array $array
     *
     * @return KoruData
     */

    function store($array)
    {
        foreach($array as $key => $value)
            $this->set($key, $value);

        return $this;
    }




    /**
     * Get
     *
     * @param string $name
     *
     * @return mixed
     */

    function get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }




    /**
     * Set
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return KoruData
     */

    function set($name, $value)
    {
        $this->data[$name] = $value;

        return $this;
    }




    /**
     * Is Empty
     *
     * @param string $name   The name of the data.
     *
     * @return bool
     */

    function isEmpty($name)
    {
        return empty($this->get($name));
    }




    /**
     * Is Null
     *
     * @param string $name   The name of the data.
     *
     * @return bool
     */

    function isNull($name)
    {
        return is_null($this->get($name));
    }




    /**
     * Is Troubled
     *
     * Set the data is invalid/troubled or not.
     *
     * @param bool $set
     *
     * @return KoruData
     */

    function isTroubled($set = false)
    {
        if($set)
            $this->isTroubled = true;
        else
            return $this->isTroubled;

        return $this;
    }




    /**
     * Output Keys
     *
     * Output an array which fills with the data names.
     *
     * @return array
     */

    function outputKeys()
    {
        $keys = [];

        foreach($this->data as $key => $value)
            array_push($keys, $key);

        return $keys;
    }




    /**
     * Output
     *
     * Push the datas into a single array and output it.
     *
     * @param string $outputs   The values to output, seperate by commas.
     *
     * @return array
     */

    function output($outputs = null)
    {
        if($outputs)
        {
            $outputs = explode(',', $outputs);

            $data = [];

            foreach($outputs as $output)
            {
                $output = str_replace(["\n", "\r", ' '], '', $output);

                if(isset($this->data[$output]))
                    $data[$output] = $this->data[$output];
            }

            return $data;
        }

        return $this->data;
    }
}
?>