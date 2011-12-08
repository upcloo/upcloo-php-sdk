<?php 
/**
 * 
 * The base model object
 *
 * @author Walter Dal Mut
 * @package UpCloo_Model
 * @license MIT
 *
 * Copyright (C) 2011 Walter Dal Mut, Gabriele Mittica
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
class UpCloo_Model_Base
    implements ArrayAccess, Iterator, Countable
{
    private $_position = 0;
    protected $_container = array();
    
    
    public function __construct() {
        $this->_position = 0;
        $this->_container = array();
    }
    
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->_container[] = $value;
        } else {
            $this->_container[$offset] = $value;
        }
    }
    
    public function offsetExists($offset) {
        return isset($this->_container[$offset]);
    }
    
    public function offsetUnset($offset) {
        unset($this->_container[$offset]);
    }
    
    public function offsetGet($offset) {
        return isset($this->_container[$offset]) ? $this->_container[$offset] : null;
    }
    
    public function rewind() {
        $this->_position = 0;
    }
    
    public function current() {
        return $this->_container[$this->_position];
    }
    
    public function key() {
        return $this->_position;
    }
    
    public function next() {
        ++$this->_position;
    }
    
    public function valid() {
        return isset($this->_container[$this->_position]);
    }

    /**
     * Count elements
     * 
     * @return int The number of elements
     */
    public function count() {
        return count($this->_container);
    }

    /**
     * Retrive the XML representation of this object
     * 
     * @return string The XML representation of this object
     * 
     * @todo fix this using dom elements.
     */
    public function asXml()
    {
        return $this->_asXml(array("model" => $this->_container));
    }
    
    protected function _setContainer(array $container)
    {
        $this->_container = $container;
    }
    
    /**
     * Convert this model to XML representation
     * 
     * @param array $model
     */
    private function _asXml($model)
    {
        if (is_string($model)) {
            return "<![CDATA[" . strip_tags($model) . "]]>";
        } else {
            $xml = "";
            if ($model && is_array($model)) {
                foreach ($model as $key => $value) {
                    if (is_int($key)) {
                        $key = "element";
                    }
                    $xml .= "<{$key}>" . $this->_asXml($value) . "</{$key}>";
                }
            }
        
            return $xml;
        }
    }
    
    /**
     * Retrive the string object representation
     * 
     * @return string The XML string representation
     */
    public function __toString()
    {
        return $this->asXml();
    }
    
    public static function fromArray(array $model)
    {
        $m = new self();
        $m->_setContainer($model);
        $m->rewind();
        
        return $m;
    }
}