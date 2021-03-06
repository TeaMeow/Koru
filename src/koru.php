<?php

/**
 * The Class which build the KoruData.
 */

class Koru
{
    /**
     * Build
     *
     * @param array|bool $data        Set false when we want to build the data from `php://input`.
     * @param array      $extraData
     */

    static function build($data = null, $extraData = null)
    {
        $fromInput = $data === false;
        $data      = $data      ?: [];
        $extraData = $extraData ?: [];
        $input     = [];

        if($fromInput)
            parse_str(file_get_contents('php://input'), $input);

        if(is_array($extraData))
            $data = array_merge($data, $extraData, $input);
        else
            $data = array_merge($data, $input);

        if(!is_string($extraData))
            return new KoruData($data);

        $koru = new KoruData($data);
        $koru->keep($extraData);

        return $koru;
    }
}




/**
 * The data helper class.
 */

class KoruData
{
    /**
     * Stores the main data here.
     *
     * @var array
     */

    private $data = [];

    /**
     * Set true when the data is invalid or corrupt.
     *
     * @var bool
     */

    private $isCorrupt = false;




    /**
     * CONSTRUCT
     */

    function __construct($data)
    {
        foreach($data as $key => $value)
            $this->data[$key] = $value;
    }




    /**
     * CALL
     *
     * Used to handle some PHP keywords or .. ALL.
     *
     * @return mixed
     */

    public function __call($name, $args)
    {
        $basicFunctions = ['declare'];

        if(in_array($name, $basicFunctions))
            return call_user_func_array(array($this, '_' . $name), $args);
    } // @codeCoverageIgnore




    /**
     * GET
     *
     * @param string $name   The name of the data.
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
     * @param string $name    The name of the data.
     * @param mixed  $value   The value of the data.
     *
     * @return KoruData
     */

    function __set($name, $value)
    {
        return $this->set($name, $value);
    }




    /**
     *
     *
     */

    function _declare($data)
    {
        $this->data = [];

        $this->set($data);

        return $this;
    }




    /**
     * Decode a json data and replace the original data.
     *
     * @param string $name   The namd of the data to decode.
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
     * Specify the datas to remove.
     *
     * @param string $variables   The name of the data to clean, seperate with commas (ex: a, b, c).
     *
     * @return KoruData
     */

    function remove($variables)
    {
        $variables = $this->commaToArray($variables);

        foreach($this->data as $key => $value)
        {
            foreach($variables as $variable)
                if($key === $variable)
                    unset($this->data[$key]);
        }

        return $this;
    }




    /**
     * Convert commas to an array.
     *
     * @param  string $variables
     *
     * @return array
     */

    function commaToArray($variables)
    {
        $outputs   = explode(',', $variables);
        $variables = [];

        foreach($outputs as $output)
            $variables[] = str_replace(["\n", "\r", ' '], '', $output);

        return $variables;
    }




    /**
     * Clean all the datas expect some.
     *
     * @param string $variables   The name of the data to keep, seperate with commas (ex: a, b, c).
     *
     * @return KoruData
     */

    function keep($variables)
    {
        $variables = $this->commaToArray($variables);

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
     * Get the data
     *
     * @param string $name
     *
     * @return mixed
     */

    function get($name = null, $default = 'KORU_UNDEFINED')
    {
        if(strpos($name, ','))
        {
            $data = [];

            foreach($this->commaToArray($name) as $name)

                /** Set the data as a default value f user set the default value */
                if($default !== 'KORU_UNDEFINED')
                    $data[$name] = isset($this->data[$name]) ? $this->data[$name] : $default;

                /** Otherwise we don't push it into the output array */
                else
                    if(isset($this->data[$name]))
                        $data[$name] = $this->data[$name];
        }
        else if($name === null)
        {
            return $this->data;
        }
        else
        {
            $data = isset($this->data[$name]) ? $this->data[$name] : null;
        }

        return $data;
    }




    /**
     * Set
     *
     * @param string|array $name
     * @param mixed        $value
     *
     * @return KoruData
     */

    function set($name, $value = null)
    {
        if(is_array($name))
        {
            foreach($name as $key => $value)
                $this->data[$key] = $value;
        }
        else
        {
            if($name !== null || $name !== false)
                $this->data[$name] = $value;
        }

        return $this;
    }




    /**
     * Is the data empty?
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
     * Is the data null?
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
     * Set the data is invalid/corrupt or not.
     *
     * @param bool $set
     *
     * @return KoruData
     */

    function isCorrupt($set = false)
    {
        if($set)
            $this->isCorrupt = true;
        else
            return $this->isCorrupt;

        return $this;
    }




    /**
     * Returns true when there're only the datas which we wanted, no other datas.
     *
     * @param  string $dataNames     The data names.
     *
     * @return bool
     */

    function only($dataNames)
    {
        $names = $this->commaToArray($dataNames);
        $keys  = [];

        foreach($this->data as $key => $value)
            array_push($keys, $key);

        foreach($names as $name)
        {
            if(!isset($this->data[$name]))
                return false;

            $keys = array_diff($keys, [$name]);
        }

        return empty($keys);
    }




    /**
     * Returns true when the datas which we wanted do exist.
     *
     * @param string $dataNames     The data names.
     * @param bool   $relaxMode     Set true when NOT all the data must be existed.
     *
     * @return bool
     */

    function has($dataNames, $relaxMode = false)
    {
        $names = $this->commaToArray($dataNames);
        $keys  = [];

        foreach($this->data as $key => $value)
            array_push($keys, $key);

        foreach($names as $name)
            if(isset($this->data[$name]) && $relaxMode)
                return true;
            if(!isset($this->data[$name]))
                return false;

        return true;
    }




    /**
     * Output an array which fills with the data names.
     *
     * @return array
     */

    function getKeys()
    {
        $keys = [];

        foreach($this->data as $key => $value)
            array_push($keys, $key);

        return $keys;
    }
}
?>