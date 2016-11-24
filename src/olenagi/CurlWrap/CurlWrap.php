<?php
/**
 * Created by PhpStorm.
 * User: olenagi
 * Date: 04.11.16
 * Time: 1:31
 */

namespace olenagi\CurlWrap;


class CurlWrap
{

    private $resource;
    private $options = [];

    /**
     * Curl constructor.
     * @param string $url
     * @param bool $returnTransfer
     * @throws \Exception
     */
    public function __construct($url = '', $returnTransfer = true)
    {
        if (!extension_loaded('curl')) {
            throw new \Exception("cURL extension not found");
        }
        $this->init($url);

        if ($returnTransfer) {
            $this->setOpt(CURLOPT_RETURNTRANSFER, true);
        }
    }

    /**
     * @param string $url
     *  Initialization
     */
    public function init($url = '')
    {
        $this->resource = curl_init($url);
    }

    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
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
     * Get option value by name
     *
     * @param $name
     * @return bool|mixed
     */
    public function getOpt($name)
    {
        return isset($this->options[$name]) ? $this->options[$name] : false;
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
     * Set file for upload
     *
     * @param $path
     * @return bool
     * @throws CurlWrapException
     */
    public function setFile($path)
    {
        if(!file_exists($path)){
            throw new CurlWrapException('File not found');
        }

        return $this->setPostField('file', new \CURLFile($path));
    }

    /**
     * Set post fields
     *
     * @param $data
     * @return bool
     */
    public function setPostFields($data)
    {
        if ($postFields = $this->getOpt(CURLOPT_POSTFIELDS)) {
            $data = array_merge($postFields, $data);
        }
        return $this->setOpt(CURLOPT_POSTFIELDS, $data);
    }

    /**
     * @param mixed $name
     * @param mixed $value
     * @return bool
     */
    public function setPostField($name, $value)
    {
        return $this->setPostFields([$name => $value]);
    }


    /**
     * Make request by GET method
     *
     * @param string $url
     * @param array $params
     * @return CurlResponse
     */

    public function get($url = '', $params = [])
    {
        $this->setOpt(CURLOPT_HTTPGET, true);

        $url = $url.'?'.http_build_query($params);
        $this->setUrl($url);

        return $this->exec();
    }

    /**
     * Make request by POST method
     *
     * @param string $url
     * @param array $data
     * @return CurlResponse
     */
    public function post($data = [], $url = '')
    {
        $this->setUrl($url);
        $this->setOpt(CURLOPT_POST, true);
        $this->setPostFields($data);

        return $this->exec();
    }

    /**
     * Make request by PUT method
     *
     * @param string $url
     * @param array $data
     * @return CurlResponse
     */
    public function put($data = [], $url = '')
    {
        $this->setUrl($url);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, "PUT");
        $this->setPostFields($data);

        return $this->exec();
    }

    /**
     * @param string $url
     * @param array $data
     * @return CurlResponse
     */
    public function delete($url = '', $data= [])
    {
        $this->setUrl($url);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, "DELETE");
        $this->setPostFields($data);

        return $this->exec();
    }

    /**
     * Exec resource
     *
     * @return CurlResponse
     */
    private function exec()
    {
        $response = new CurlResponse(
            curl_exec($this->resource),
            curl_errno($this->resource),
            curl_error($this->resource),
            curl_getinfo($this->resource)
        );

        return $response;
    }

    /**
     * Close resource
     *
     * @return mixed
     */
    public function close()
    {
        return curl_close($this->resource);
    }

    /**
     * Reset
     */
    public function reset()
    {
        curl_reset($this->resource);
        $this->options = [];
        $this->resource = null;
    }
}