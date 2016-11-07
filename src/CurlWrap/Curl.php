<?php
/**
 * Created by PhpStorm.
 * User: olenagi
 * Date: 04.11.16
 * Time: 1:31
 */

namespace CurlWrap;


class Curl
{
    private $resource;

    /**
     * Curl constructor.
     * @param string $url
     * @throws \Exception
     */
    public function __construct($url = '')
    {
        if (!extension_loaded('curl')) {
            throw new \Exception("cURL extension not found");
        }

        $this->resource = curl_init();
        $this->setUrl($url);
    }

    /**
     * Set url for curl
     * @param $url
     * @return bool
     */
    public function setUrl($url)
    {
        return $url ? $this->setOpt(CURLOPT_URL, $url) : false;
    }

    /**
     * Set option
     *
     * @param $option
     * @param $value
     * @return bool
     */
    public function setOpt($option, $value)
    {
        $result = curl_setopt($this->resource, $option, $value);
        return $result;
    }

    /**
     * Make request by GET method
     *
     * @param $url
     * @return mixed
     */
    public function get($url = '')
    {
        $this->setOpt(CURLOPT_HTTPGET, true);
        $this->setUrl($url);

        return $this->request();
    }

    /**
     * Make request by POST method
     *
     * @param string $url
     * @param array $data
     * @return mixed
     */
    public function post($data = [], $url = '')
    {
        $this->setUrl($url);

        $this->setOpt(CURLOPT_POST, true);
        $this->setOpt(CURLOPT_POSTFIELDS, $data);

        return $this->request();
    }

    /**
     * Make request
     *
     * @return mixed
     */
    private function request()
    {
        $result = curl_exec($this->resource);
        return $result;
    }
}