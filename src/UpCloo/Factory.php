<?php
/**
 *
 * UpCloo Factory
 *
 * This class implements the Factory method for UpCloo
 * Manager.
 *
 * @author Walter Dal Mut
 * @package UpCloo
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
class UpCloo_Factory
{
    /**
     * Create an UpCloo instance using the
     * factory method.
     *
     * @param string $clientName
     * @param array $options
     * @throws RuntimeException
     *
     * @return UpCloo_Manager
     */
    public static function factory($clientName = 'UpCloo', array $options = array())
    {
        $upcloo = new UpCloo_Manager();

        if (!@class_exists($clientName)) {
            $prefix = 'UpCloo_Client';
            if (!@class_exists($prefix . "_" . $clientName)) {
                throw new RuntimeException("You have to use an existing client");
            } else {
                $clientName = $prefix . "_" . $clientName;
            }
        }

        $client = new $clientName();
        $upcloo->setClient($client);

        $sitekey = array_key_exists("sitekey", $options) ? $options["sitekey"] : '';

        $upcloo->setCredential($sitekey);

        return $upcloo;
    }
}
