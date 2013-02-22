<?php
/**
 *
 * The UpCloo HTTP Client
 *
 * @author Walter Dal Mut
 * @package UpCloo_Client
 * @license MIT
 *
 * Copyright (C) 2012 UpCloo Ltd.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
class UpCloo_Client_UpCloo implements UpCloo_Client_ClientInterface
{
    /**
     * The http client
     *
     * @var UpCloo_Http_Client
     */
    protected $_client;

    /**
     * The username
     *
     * @var string The username
     */
    private $_username;

    /**
     * The sitekey
     *
     * @var string The sitekey
     */
    private $_sitekey;

    /**
     * Client constructor
     *
     * Create the default client.
     *
     */
    public function __construct($client = false)
    {
        if (!$client) {
            $this->_client = new UpCloo_Http_Client();
        } else {
            $this->_client = $client;
        }
    }

    /**
     * Get the Http Client
     *
     * @return UpCloo_Http_Client The HTTP Client
     */
    public function getHttpClient()
    {
    	return $this->_client;
    }

    /**
     * @see UpCloo_Client_ClientInterface::get()
     */
    public function get($id, $vsitekey = false)
    {
        $uri = '';
        if (!$vsitekey) {
            $uri = sprintf(UpCloo_Manager::REPOSITORY, $this->getRegion(), $this->getSiteKey());
        } else {
            $uri = sprintf(UpCloo_Manager::REPOSITORY, $this->getRegion(), $this->getSiteKey());
            $uri .= "/%s";
            $uri = sprintf($uri, $vsitekey);
        }
        $uri .= "/" . base64_encode($id);
        $this->_client->setUri($uri);

        $dataPack = $this->_getFromRepository($uri);

        $elements = json_decode($dataPack);

        return $elements;
    }

    /**
     * Retrive from repo
     *
     * @param string $uri
     *
     * @return array
     */
    protected function _getFromRepository()
    {
        $response = $this->_client->request("get");

        if ($response->getStatus() == 200) {
            return $response->getBody();
        } else {
            return array();
        }
    }

    /**
     * Set the username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->_username = $username;
    }

    /**
     * Retrive the username
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * Set the site key
     *
     * @param string $sitekey
     */
    public function setSiteKey($sitekey)
    {
        $sitekey = trim($sitekey);
        if (preg_match('/[^a-z_\-0-9]/i', $sitekey)) {
            throw new RuntimeException("Sitekey should be only contains alphanumeric characters or _-");
        }
        $this->_sitekey = $sitekey;
    }

    /**
     *
     * Retrive the site key
     *
     * @return string The site key
     */
    public function getSiteKey()
    {
        return trim($this->_sitekey);
    }

    /**
     * Locate UpCloo region from the sitekey
     *
     * Eg. it-xx00XXxx the "it" region is returned
     *
     * @return string The region
     */
    public function getRegion()
    {
        $region = $this->getSiteKey();
        if (!$region) {
            throw new RuntimeException("You have to set the sitekey");
        }

        $tokens = explode("-", $this->getSitekey());
        $region = array_shift($tokens);

        if (!$region || $region == '') {
            throw new RuntimeException("Unable to locate region");
        }

        return trim($region);
    }

    /**
     * Retrive the uri of latest request
     *
     * @return string;
     */
    public function getUri()
    {
        return $this->_client->getUri(true);
    }
}
