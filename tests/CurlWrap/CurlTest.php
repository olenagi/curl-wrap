<?php

/**
 * Created by PhpStorm.
 * User: olenagi
 * Date: 04.11.16
 * Time: 14:31
 */

use \PHPUnit\Framework\TestCase;
use CurlWrap\Curl;


class CurlTest extends TestCase
{
    public function testGetMethod()
    {
        $curl = new Curl('http://setkadeneg.ru');
        $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $result = $curl->get();
        $this->assertTrue((bool) $result);
    }

    public function testPostMethod()
    {
        $curl = new Curl('http://check.loc');
        $result = $curl->post(['test_time' => time()]);
        $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        var_dump($result);
        $this->assertTrue((bool) $result);
    }
}