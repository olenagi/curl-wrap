<?php

/**
 * Created by PhpStorm.
 * User: olenagi
 * Date: 04.11.16
 * Time: 14:31
 */

use \PHPUnit\Framework\TestCase;
use olenagi\CurlWrap\CurlWrap;


class CurlTest extends TestCase
{
    public $url = 'http://check.loc/index.php';
    public $filePath = __DIR__.'/files/file.txt';

    public function testMethods()
    {
        $curl = new CurlWrap();
        $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $curl->setUrl($this->url);
        $response = $curl->get();
        $this->assertTrue($response->isOk());


        $response = $curl->delete();
        $this->assertTrue($response->isOk());


        $curl = new CurlWrap();
        $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $curl->setFile($this->filePath);
        $curl->setUrl($this->url);
        $response = $curl->post();
        $this->assertTrue($response->isOk());

        $response = $curl->put();
        $this->assertTrue($response->isOk());
    }

    public function testSendFile()
    {
        $curl = new CurlWrap($this->url);
        $curl->setFile($this->filePath);
        $response = $curl->post();
        $this->assertTrue($response->isOk());

    }
}