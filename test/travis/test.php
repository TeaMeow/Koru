<?php
error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);

include 'src/koru.php';

class Test extends PHPUnit_Framework_TestCase
{
    function __construct()
    {

    }

    function testEmptyBuild()
    {
        $data = Koru::build();
    }

    function testArrayBuild()
    {
        $data = Koru::build(['username' => 'test',
                             'password' => 'test123']);
    }

    function testClean()
    {
        $data = Koru::build(['username' => 'test',
                             'password' => 'test123'])->clean('username');

        if($data->username !== null)
            $this->fail('Koru can\'t clean the right data.');
    }

    function testLeave()
    {
        $data = Koru::build(['username' => 'test',
                             'password' => 'test123'])->leave('username');

        if($data->username === null)
            $this->fail('Koru can\'t leave the right data.');

        if($data->password !== null)
            $this->fail('Koru can\'t clean the right data when leave().');
    }

    function testIsNull()
    {
        $data = Koru::build(['username' => 'test',
                             'password' => 'test123']);

        if(!$data->isNull('test'))
            $this->fail('Koru can\'t tell when the data is null.');

        if($data->isNull('username'))
            $this->fail('Koru is having some problems with isNull().');
    }

    function testIsEmpty()
    {
        $data = Koru::build(['username' => ['A', 'B'],
                             'password' => []]);

        if(!$data->isEmpty('password'))
            $this->fail('Koru can\'t tell when the data is empty.');

        if($data->isEmpty('username'))
            $this->fail('Koru is having some problems with isEmpty().');
    }

    function testStore()
    {
        $data = Koru::build(['username' => ['A', 'B'],
                             'password' => []]);

        $data->store(['A' => 'B']);

        if($data->isNull('A') || $data->A !== 'B')
            $this->fail('Koru is having some problems with store().');
    }

    function testOutputKeys()
    {
        $data = Koru::build(['username' => ['A', 'B'],
                             'password' => []]);

        $keys = $data->outputKeys();
    }

    function testOutput()
    {
        $data = Koru::build(['username' => ['A', 'B'],
                             'password' => []]);

        $keys = $data->output();
    }

    function testJsonDecode()
    {
        $data = Koru::build(['username' => '[]']);

        if(!is_array($data->jsonDecode('username')))
            $this->fail('Koru cannot decode the json.');
    }
}

?>