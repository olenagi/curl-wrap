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
    protected $url;

    /**
     * Curl constructor.
     * @param string $url
     * @param array $params
     * @param bool $returnTransfer
     * @throws \Exception
     */
    public function __construct($url = '', $params = [])
    {
        if (!extension_loaded('curl')) {
            throw new \Exception("cURL extension not found");
        }
        $this->init();
        $this->setUrl($url, $params);
    }

    /**
     *  Initialization
     */
    private function init()
    {
        $this->resource = curl_init();
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

    public function __clone()
    {
        $this->resource = curl_copy_handle($this->resource);
    }

    function __destruct()
    {
        $this->close();
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
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
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
     * @param mixed $name
     * @param mixed $value
     * @return bool
     */
    public function setPostField($name, $value)
    {
        return $this->setPostFields([$name => $value]);
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
     * Reset
     */
    public function reset()
    {
        curl_reset($this->resource);
        $this->options = [];
        $this->resource = null;
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
     * Get version info
     *
     * @return array
     */
    public function version()
    {
        return curl_version();
    }

    /**
     * Get options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }


    /**
     * Set url for curl
     * @param $url
     * @param array $params
     * @return bool
     * @throws CurlWrapException
     */
    public function setUrl($url, $params = [])
    {
        if ($url && $params && is_array($params)) {
            //TODO Make lib for add params in url. This is simple method
            $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') .  http_build_query($params);
        }

        $this->url = $url;
        return $this->setOpt(CURLOPT_URL, $url);
    }

    /**
     * Get url
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }


}
