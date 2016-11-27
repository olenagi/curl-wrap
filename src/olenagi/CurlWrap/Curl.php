<?php
/**
 * Created by PhpStorm.
 * User: olenagi
 * Date: 04.11.16
 * Time: 1:31
 */

namespace olenagi\CurlWrap;


class Curl extends BaseCurl
{
    /**
     * Set file for upload
     *
     * @param $path
     * @return bool
     * @throws CurlException
     */
    public function setFile($path)
    {
        if (!file_exists($path)) {
            throw new CurlException('File not found');
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

    public function get()
    {
        $this->setOpt(CURLOPT_HTTPGET, true);
        return $this->exec();
    }

    /**
     * Make request by POST method
     *
     * @return CurlResponse
     */
    public function post()
    {
        $this->setOpt(CURLOPT_POST, true);
        return $this->exec();
    }

    /**
     * Make request by PUT method
     *
     * @return CurlResponse
     */
    public function put()
    {
        $this->setOpt(CURLOPT_CUSTOMREQUEST, "PUT");
        return $this->exec();
    }

    /**
     * @return CurlResponse
     */
    public function delete()
    {
        $this->setOpt(CURLOPT_CUSTOMREQUEST, "DELETE");
        return $this->exec();
    }
}