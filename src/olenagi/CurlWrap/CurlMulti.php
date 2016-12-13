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
    public $resource;
    public $stillRunning;
    private $childResources = [];

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
        $this->childResources[] = $childResource;
        return curl_multi_add_handle($this->resource, $childResource);
    }
    
    /**
     * @return int
     */
    public function exec()
    {
        return curl_multi_exec($this->resource, $this->stillRunning);
    }

    /**
     * @return mixed
     */
    public function getStillRunning()
    {
        return $this->stillRunning;
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
    public function infoRead()
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
        $key = array_search($childResource, $this->childResources);
        unset($this->childResources[$key]);
        return curl_multi_remove_handle($this->resource, $childResource);
    }

    /**
     * Close multi curl
     */
    public function close()
    {
        foreach ($this->childResources as $childResource) {
            curl_close($childResource);
        }
        return curl_multi_close ( $this->resource );
    }

    /**
     * Execute all handler
     *
     * Array of response objects
     * @return array
     */
    public function run()
    {
        $result = [];
        do {
            $code = $this->exec();
        }
        while ($code == CURLM_CALL_MULTI_PERFORM);

        while ($this->getStillRunning() && ($code == CURLM_OK)) {
            if ($this->select() != -1) {
                do {
                    $code = $this->exec();
                    while ($info = $this->infoRead()) {
                        $resource = $info['handle'];
                        $result[] = new CurlResponse($resource);
                    }
                } while($code == CURLM_CALL_MULTI_PERFORM);
            }
        }

        return $result;
    }
}