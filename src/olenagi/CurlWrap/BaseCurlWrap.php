<?php
/**
 * Created by PhpStorm.
 * User: olenagi
 * Date: 04.11.16
 * Time: 1:31
 */

namespace olenagi\CurlWrap;


class BaseCurlWrap
{

    protected $resource;
    protected $options = [];

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

    public function __clone()
    {
        $this->resource = curl_copy_handle($this->resource);
    }

    function __destruct()
    {
        $this->close();
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
     * Set array of options
     *
     * @param $options
     * @return bool
     */
    public function setOpts($options)
    {
        $this->options = array_merge($this->options, $options);
        $result = curl_setopt_array($this->resource, $options);
        return $result;
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
     * Exec resource
     *
     * @return CurlResponse
     */
    protected function exec()
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