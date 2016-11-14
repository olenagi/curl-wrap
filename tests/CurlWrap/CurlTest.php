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
        $curl = new Curl('http://check.loc');
//        $curl->setOpt(CURLOPT_VERBOSE, true);
        $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
//        $curl->setOpt(CURLOPT_FOLLOWLOCATION, false);
        //$curl->setOpt(CURLOPT_HEADER, true);
        $this->assertTrue(file_exists(__DIR__.'/files/file.txt'));
        $curl->setOpt(CURLOPT_POSTFIELDS, ['file_contents'=> curl_file_create(__DIR__.'/files/file.txt')]);
        $result = $curl->post();
        print_r($result);
        $this->assertTrue((bool) $result);
    }

//    public function testPostMethod()
//    {
//        $curl = new Curl('http://check.loc');
//        $result = $curl->post(['test_time' => time()]);
//        //$curl->setOpt(CURLOPT_RETURNTRANSFER, true);
//        $this->assertTrue((bool) $result);
//    }
}