<?php
require 'koru.php';

class KoruTest extends \PHPUnit_Framework_TestCase
{
    function testBuild()
    {
        $data = Koru::build(['username' => 'foobar']);

        $this->assertEquals($data->username, 'foobar');
    }

    function testExtraBuild()
    {
        $data = Koru::build(['username' => 'foobar'], ['password' => 'moonDalan']);

        $this->assertEquals($data->password, 'moonDalan');
    }

    function testBuildInput()
    {
        $data = Koru::build(false);

        $this->assertEquals($data, []);
    }

    function testMultipleGet()
    {
        $data = ['username' => 'foobar',
                 'password' => 'moonDalan'];

        $koru = Koru::build($data);

        $this->assertEquals($koru->get('username, password'), $data);
    }

    function testSet()
    {
        $data = Koru::build();

        $data->set('username', 'foobar');

        $this->assertEquals(data->username, 'foobar');
    }

    function testMultipleSet()
    {
        $data = ['username' => 'foobar',
                 'password' => 'moonDalan'];

        $koru = Koru::build();

        $koru->set($data);

        $this->assertEquals($koru->get('username', 'password'), $data);
    }

    function testJsonDecode()
    {
        $data = Koru::build(['username' => '[]']);

        $this->assertEquals($data->jsonDecode('username')->username, []);
    }

    function testIsNull()
    {
        $data = Koru::build(['username' => 'foobar',
                             'password' => 'moonDalan']);

        $this->assertEquals($data->isNull('test')    , true);
        $this->assertEquals($data->isNull('username'), false);
    }

    function testIsEmpty()
    {
        $data = Koru::build(['username' => ['A', 'B'],
                             'password' => []]);

        $this->assertEquals($data->isEmpty('password'), true);
        $this->assertEquals($data->isEmpty('username'), false);
    }

    function testKeep()
    {
        $data = Koru::build(['username' => 'foobar',
                             'password' => 'moonDalan']);

        $data->keep('username');

        $this->assertEquals($data->get(), ['username' => 'foobar']);
    }

    function testRemove()
    {
        $data = Koru::build(['username' => 'foobar',
                             'password' => 'moonDalan']);

        $data->remove('password');

        $this->assertEquals($data->get(), ['username' => 'foobar']);
    }

    function testIsCorrupt()
    {
        $data = Koru::build();

        $this->assertEquals($data->isCorrupt, false);

        $data->isCorrupt(true);

        $this->assertEquals($data->isCorrupt, true);
    }

    function testOnly()
    {
        $data = Koru::build(['username' => 'foobar',
                             'password' => 'moonDalan',
                             'birthday' => '1998-07-13']);

        $this->assertEquals($data->only('username, password'), false);
        $this->assertEquals($data->only('username, password, birthday'), true);
    }

    function testHas()
    {
        $data = Koru::build(['username' => 'foobar',
                             'password' => 'moonDalan']);

        $this->assertEquals($data->has('username, password'), true);
        $this->assertEquals($data->has('username, birthday'), true);
        $this->assertEquals($data->has('username, birthday', true), false);
    }

    function testOutputKeys()
    {
        $data = Koru::build(['username' => 'foobar',
                             'password' => 'moonDalan']);

        $this->assertEquals($data->outputKeys(), ['username', 'password']);
    }
}
?>