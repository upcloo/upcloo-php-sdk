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
    implements ArrayAccess, Countable
{
    /**
     * The data container
     * 
     * @var array The container
     */
    protected $_container = array();
    
    /**
     * Create the model
     * 
     * This model is useful for index new contents
     * or updates existing one.
     * 
     * @param int|string $id The content identification
     */
    public function __construct($id = false) {
        $this->_container = array();
        
        if ($id !== false) {
            $this["id"] = $id;
        }
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
     */
    public function asXml()
    {
        $document = new DOMDocument("1.0", "utf-8");
        $this->_asXml(array("model" => $this->_container), null, $document);
        
        return $document->saveXML();
    }
    
    /**
     * Create the container starting from one
     * 
     * @param array $container Your actual container
     * 
     * @see UpCloo_Model_Base::fromArray()
     */
    protected function _setContainer(array $container)
    {
        $this->_container = $container;
    }
    
    /**
     * Convert this model to XML representation
     * 
     * @param array|string $model
     * @param DOMNode $root
     * @param DOMDocument $document
     */
    private function _asXml($model, $root, $document)
    {
        if (is_string($model)) {
            $element = $document->createTextNode($model);
            $root->appendChild($element);
        } else {
            if ($model && is_array($model)) {
                foreach ($model as $key => $value) {
                    if (is_int($key)) {
                        $key = 'element';
                    }
                     
                    $element = $document->createElement($key);
                    $this->_asXml($value, $element, $document);
                    
                    if ($root === null) {
                        $document->appendChild($element);
                    } else {
                        $root->appendChild($element);
                    }
                }
            }
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
    
    /**
     * Create a model instance starting from an array
     * 
     * @param array $model Your array model
     */
    public static function fromArray(array $model)
    {
        $m = new self();
        $m->_setContainer($model);
        
        return $m;
    }
}