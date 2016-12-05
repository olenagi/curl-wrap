<?php
/**
 * Created by PhpStorm.
 * User: olenagi
 * Date: 21.11.16
 * Time: 23:16
 */

namespace olenagi\CurlWrap;


class CurlResponse
{
    private $errorNum = 0;
    private $errorMsg = '';
    private $info = [];
    private $content = '';

    public function __construct($content, $resource)
    {
        $this->content = $content;
        $this->errorNum = curl_errno($resource);
        $this->errorMsg = curl_error($resource);
        $this->info = curl_getinfo($resource);
    }

    /**
     * @return int
     */
    public function getErrorNum()
    {
        return $this->errorNum;
    }

    /**
     * @return string
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     * @return array
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return bool
     */
    public function isOk()
    {
        return isset($this->info['http_code']) ? $this->info['http_code'] == '200' : false;
    }


}