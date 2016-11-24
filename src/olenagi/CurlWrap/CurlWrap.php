<?php
/**
 * Created by PhpStorm.
 * User: olenagi
 * Date: 04.11.16
 * Time: 1:31
 */

namespace olenagi\CurlWrap;


class CurlWrap extends BaseCurlWrap
{
    /**
     * Set file for upload
     *
     * @param $path
     * @return bool
     * @throws CurlWrapException
     */
    public function setFile($path)
    {
        if (!file_exists($path)) {
            throw new CurlWrapException('File not found');
        }

        return $this->setPostField('file', new \CURLFile($path));
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

        $url = $url . '?' . http_build_query($params);
        $this->setUrl($url);

        return $this->exec();
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
    public function delete($url = '', $data = [])
    {
        $this->setUrl($url);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, "DELETE");
        $this->setPostFields($data);

        return $this->exec();
    }

}