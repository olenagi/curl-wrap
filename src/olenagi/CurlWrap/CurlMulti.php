<?php
/**
 * Created by PhpStorm.
 * User: olenagi
 * Date: 27.11.16
 * Time: 14:59
 */

namespace olenagi\CurlWrap;


class CurlMulti
{
    protected $resource;
    protected $childResources;
    protected $stillRunning;

    /**
     * CurlMulti constructor.
     */
    public function __construct()
    {
        $this->resource = curl_multi_init();
    }

    /**
     * @param resource $childResource
     * @return int
     */
    public function add($childResource)
    {
        return curl_multi_add_handle($this->resource, $childResource);
    }

    /**
     * @return mixed
     */
    public function getChildResources()
    {
        return $this->childResources;
    }

    /**
     * @return int
     */
    public function exec()
    {
        return curl_multi_exec($this->resource, $this->stillRunning);
    }

    /**
     * @return int
     */
    public function select()
    {
        return curl_multi_select($this->resource);
    }

    /**
     * @return array
     */
    public function info()
    {
        return curl_multi_info_read($this->resource);
    }

    /**
     * @param resource $childResource
     * @return string
     */
    public function content($childResource)
    {
        return curl_multi_getcontent($childResource);
    }

    /**
     * @param $childResource
     * @return int
     */
    public function remove($childResource)
    {
        return curl_multi_remove_handle($this->resource, $childResource);
    }
}