<?php
/**
 * Created by PhpStorm.
 * User: olenagi
 * Date: 04.11.16
 * Time: 1:31
 */

namespace olenagi\CurlWrap;


class BaseCurl
{

    protected $resource;
    protected $options = [];
    protected $url;

    /**
     * Curl constructor.
     * @param string $url
     * @param array $urlParams
     * @throws CurlException
     */
    public function __construct($url = '', $urlParams = [])
    {
        if (!extension_loaded('curl')) {
            throw new CurlException("cURL extension not found");
        }
        $this->resource = curl_init();
        $this->setUrl($url, $urlParams);
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
     * @throws CurlException
     */
    public function setOpts($options)
    {
        if (!$this->resource) {
            throw new CurlException("Need initialization");
        }

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
     * Set option
     *
     * @param $option
     * @param $value
     * @return bool
     * @throws CurlException
     */
    public function setOpt($option, $value)
    {
        if (!$this->resource) {
            throw new CurlException("Need initialization");
        }

        $this->options[$option] = $value;
        $result = curl_setopt($this->resource, $option, $value);
        return $result;
    }

    /**
     * Reset
     */
    public function reset()
    {
        if (!$this->resource) {
            throw new CurlException("Need initialization");
        }

        $this->options = [];
        $this->resource = null;
        curl_reset($this->resource);
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
     * Get url
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set url for curl
     * @param $url
     * @param array $params
     * @return bool
     * @throws CurlException
     */
    public function setUrl($url, $params = [])
    {
        if ($url && $params && is_array($params)) {
            //TODO Make lib for add params in url. This is simple method
            $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . http_build_query($params);
        }

        $this->url = $url;
        return $this->setOpt(CURLOPT_URL, $url);
    }

    /**
     * Exec curl
     * @return CurlResponse
     * @throws CurlException
     */
    protected function exec()
    {
        if (!$this->resource) {
            throw new CurlException("Need initialization");
        }

        $response = new CurlResponse(curl_exec($this->resource), $this->resource);
        return $response;
    }


}
