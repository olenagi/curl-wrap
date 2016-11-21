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
    private $options = [];
    private $errorNum = 0;
    private $errorMsg = '';
    private $headers = [];
    private $content = '';

    public function __construct($content, $headers, $errorNum,$errorMsg)
    {
        $this->content = $content;
        $this->headers = $headers;
        $this->errorMsg = $errorNum;
        $this->errorMsg = $errorMsg;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
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
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }


}