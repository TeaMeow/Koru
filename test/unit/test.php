<?php
error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);

include 'src/koru.php';

class Test extends PHPUnit_Framework_TestCase
{
    /**
     * Test a empty build.
     */

    function testEmptyBuild()
    {
        $data = Koru::build();
    }




    /**
     * Test a build from the input.
     */

    function testInputBuild()
    {
        $data = Koru::buildInput();
    }




    /**
     * Test a build from an array.
     */

    function testArrayBuild()
    {
        $data = Koru::build(['username' => 'test',
                             'password' => 'test123']);
    }




    /**
     * Test isTroubled function.
     */

    function testisTroubled()
    {
        $data = Koru::build();

        $data->isTroubled(true);

        if(!$data->isTroubled())
            $this->fail('Koru can\'t mark a data as troubled.');
    }




    /**
     * Test the only function.
     */

    function testOnly()
    {
        $data = Koru::build(['username' => 'test',
                             'password' => 'test123',
                             'email'    => 'test@test.com']);

        if($data->only('username, password'))
            $this->fail('Koru can\'t tell when there are NOT only those datas.');

        if(!$data->only('username, password, email'))
            $this->fail('Koru is having some problems with only().');
    }




    /**
     * Test the must function.
     */

    function testMust()
    {
        $data = Koru::build(['username' => 'test',
                             'password' => 'test123',
                             'email'    => 'test@test.com']);

        if($data->must('birthday'))
            $this->fail('Koru can\'t tell when the required data is missing.');
        exit(var_dump($data));
        if(!$data->must('username'))
            $this->fail('Koru is having some problems with must().');
    }




    /**
     * Test the set function.
     */

    function testSet()
    {
        $data = Koru::build();

        $data->set('test', 'testtest');

        if(!$data->must('test'))
            $this->fail('Koru can\'t set a data.');
    }




    /**
     * Test the clean function.
     */

    function testClean()
    {
        $data = Koru::build(['username' => 'test',
                             'password' => 'test123'])->clean('username');

        if($data->username !== null)
            $this->fail('Koru can\'t clean the right data.');
    }




    /**
     * Test the leave function.
     */

    function testLeave()
    {
        $data = Koru::build(['username' => 'test',
                             'password' => 'test123'])->leave('username');

        if($data->username === null)
            $this->fail('Koru can\'t leave the right data.');

        if($data->password !== null)
            $this->fail('Koru can\'t clean the right data when leave().');
    }




    /**
     * Test the isNull function.
     */

    function testIsNull()
    {
        $data = Koru::build(['username' => 'test',
                             'password' => 'test123']);

        if(!$data->isNull('test'))
            $this->fail('Koru can\'t tell when the data is null.');

        if($data->isNull('username'))
            $this->fail('Koru is having some problems with isNull().');
    }




    /**
     * Test the isEmpty function.
     */

    function testIsEmpty()
    {
        $data = Koru::build(['username' => ['A', 'B'],
                             'password' => []]);

        if(!$data->isEmpty('password'))
            $this->fail('Koru can\'t tell when the data is empty.');

        if($data->isEmpty('username'))
            $this->fail('Koru is having some problems with isEmpty().');
    }




    /**
     * Test the store function.
     */

    function testStore()
    {
        $data = Koru::build(['username' => ['A', 'B'],
                             'password' => []]);

        $data->store(['A' => 'B']);

        if($data->isNull('A') || $data->A !== 'B')
            $this->fail('Koru is having some problems with store().');
    }




    /**
     * Test the output keys function.
     */

    function testOutputKeys()
    {
        $data = Koru::build(['username' => ['A', 'B'],
                             'password' => []]);

        $keys = $data->outputKeys();
    }




    /**
     * Test the normal output function.
     */

    function testOutput()
    {
        $data = Koru::build(['username' => ['A', 'B'],
                             'password' => []]);

        $keys = $data->output();
    }




    /**
     * Test the json decode function.
     */

    function testJsonDecode()
    {
        $data = Koru::build(['username' => '[]']);

        if(!is_array($data->jsonDecode('username')->username))
            $this->fail('Koru cannot decode the json.');
    }
}

?>