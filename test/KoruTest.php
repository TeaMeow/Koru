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

    function testMultipleGet()
    {
        $data = ['username' => 'foobar',
                 'password' => 'moonDalan'];

        $koru = Koru::build($data);

        $this->assertEquals($koru->get('username, password'), $data);
        $this->assertEquals($koru->get('username,
                                        password'), $data);
    }

    function testDefaultValue()
    {
        $data = ['username' => 'foobar',
                 'password' => 'moonDalan'];

        $koru = Koru::build($data);

        $this->assertEquals($koru->get('username, password, birthday', false), ['username' => 'foobar',
                                                                                'password' => 'moonDalan',
                                                                                'birthday' => false]);
        $this->assertEquals($koru->get('username, password, birthday', null), ['username' => 'foobar',
                                                                                'password' => 'moonDalan',
                                                                                'birthday' => null]);
        $this->assertEquals($koru->get('username, password, birthday', 1), ['username' => 'foobar',
                                                                            'password' => 'moonDalan',
                                                                            'birthday' => 1]);
        $this->assertEquals($koru->get('username, password, birthday'), ['username' => 'foobar',
                                                                         'password' => 'moonDalan']);
    }

    function testGetAll()
    {
        $data = ['username' => 'foobar',
                 'password' => 'moonDalan'];

        $koru = Koru::build($data);

        $this->assertEquals($koru->get(), $data);
    }

    function testBuildAndKeep()
    {
        $data = ['username' => 'foobar',
                 'password' => 'moonDalan',
                 'birthday' => '1998-07-13'];

        $koru = Koru::build($data, 'username, password');

        $this->assertEquals($koru->get(), ['username' => 'foobar',
                                           'password' => 'moonDalan']);
    }

    function testDeclare()
    {
        $data = ['username' => 'foobar',
                 'password' => 'moonDalan'];

        $koru = Koru::build($data);

        $data = ['username' => 'foobar',
                 'birthday' => '1998-07-13'];

        $this->assertEquals($koru->declare($data)->get(), $data);

        $this->assertEquals($koru->declare(['username' => $koru->username,
                                            'birthday' => $koru->birthday])->get(), $data);
    }

    function testSet()
    {
        $data = Koru::build();

        $data->set('username', 'foobar');

        $this->assertEquals($data->username, 'foobar');
    }

    function testDirectlySet()
    {
        $data = Koru::build();

        $data->username = 'foobar';

        $this->assertEquals($data->username, 'foobar');
    }

    function testMultipleSet()
    {
        $data = ['username' => 'foobar',
                 'password' => 'moonDalan'];

        $koru = Koru::build();

        $koru->set($data);

        $this->assertEquals($koru->get('username, password'), $data);
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

        $this->assertEquals($data->isCorrupt(), false);

        $data->isCorrupt(true);

        $this->assertEquals($data->isCorrupt(), true);
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
        $this->assertEquals($data->has('username, password', true), true);
        $this->assertEquals($data->has('username, birthday'), false);
    }

    function testGetKeys()
    {
        $data = Koru::build(['username' => 'foobar',
                             'password' => 'moonDalan']);

        $this->assertEquals($data->getKeys(), ['username', 'password']);
    }
}
?>