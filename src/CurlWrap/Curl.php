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
    private $options = [];
    private $errorNum = 0;
    private $errorMsg = '';
    private $headers = [];
    private $content = false;

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
        $this->options[$option] = $value;
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

        return $this->exec();
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

        if ($data) {
            if ($postFields = $this->getOpt(CURLOPT_POSTFIELDS)) {
                $data = array_merge($postFields, $data);
            }
            $this->setOpt(CURLOPT_POSTFIELDS, $data);
        }

        return $this->exec();
    }

    /**
     * Make request by PUT method
     *
     * @param string $url
     * @param array $data
     * @return mixed
     */
    public function put($data = [], $url = '')
    {
        $this->setUrl($url);

        $this->setOpt(CURLOPT_CUSTOMREQUEST, "PUT");

        $this->setOpt(CURLOPT_POSTFIELDS, $data);

        return $this->exec();
    }

    public function delete($url = '')
    {
        $this->setUrl($url);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, "DELETE");

        return $this->exec();
    }

    /**
     * Exec resource
     *
     * @return mixed
     */
    private function exec()
    {
        $this->content = curl_exec($this->resource);
        $this->errorNum = curl_errno($this->resource);
        $this->errorMsg = curl_error($this->resource);
        $this->headers = curl_getinfo($this->resource);

        return $this->content;
    }

    /**
     * Close resource
     *
     * @return mixed
     */
    public function close()
    {
        $this->content = '';
        $this->errorNum = 0;
        $this->errorMsg = '';
        $this->headers = [];

        return curl_close($this->resource);
    }

    public function getHeaders()
    {
        return $this->headers;
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
     * Get option value by name
     *
     * @param $name
     * @return bool|mixed
     */
    public function getOpt($name)
    {
        return isset($this->options[$name]) ? $this->options[$name] : false;
    }
}