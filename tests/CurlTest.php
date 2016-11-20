<?php

/**
 * Created by PhpStorm.
 * User: olenagi
 * Date: 04.11.16
 * Time: 14:31
 */

use \PHPUnit\Framework\TestCase;
use olenagi\CurlWrap\Curl;


class CurlTest extends TestCase
{
    public function testSendFile()
    {
        $curl = new Curl();
        $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $curl->setFile(__DIR__.'/files/file.txt');
        $result = $curl->post([], 'http://example.com');
        print_r($result);
        $this->assertTrue((bool) $result);
    }
}