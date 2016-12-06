<?php

/**
 * Created by PhpStorm.
 * User: olenagi
 * Date: 04.11.16
 * Time: 14:31
 */

use olenagi\CurlWrap\CurlMulti;
use \PHPUnit\Framework\TestCase;
use olenagi\CurlWrap\Curl;


class CurlTest extends TestCase
{
    public $url = 'http://check.loc/index.php';
    public $filePath = __DIR__.'/files/file.txt';

    public function testMethods()
    {
        $curl = new Curl();
        $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $curl->setUrl($this->url);
        $response = $curl->get();

        $this->assertTrue((bool) $response->url);
        $this->assertTrue($response->isOk());


        $response = $curl->delete();
        $this->assertTrue($response->isOk());


        $curl = new Curl();
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
        $curl = new Curl($this->url);
        $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $curl->setFile($this->filePath);
        $response = $curl->post();
        $this->assertTrue($response->isOk());

    }

    public function testCurlMulti()
    {
        $curl = new Curl();
        $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $curl->setFile($this->filePath);
        $curl->setUrl("http://check.loc/index.php");

        $curl2 = new Curl();
        $curl2->setOpt(CURLOPT_RETURNTRANSFER, true);
        $curl2->setFile($this->filePath);
        $curl2->setUrl("http://check.loc/index2.php");

        $curlMulti = new CurlMulti();
        $curlMulti->add($curl->getResource());
        $curlMulti->add($curl2->getResource());
        $result = $curlMulti->run();
        $this->assertTrue((bool) $result);
    }
}