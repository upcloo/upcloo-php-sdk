<?php 
/**
 * 
 * UpCloo Search Model
 *
 * @author Walter Dal Mut
 * @package UpCloo_Model
 * @license MIT
 *
 * Copyright (C) 2012 Corley S.R.L.
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
class UpCloo_Model_Search
{
    private $_query;
    
    private $_facets;
    private $_ranges;
    private $_filters;
    private $_networks;
    
    private $_page;
    private $_numPerPage;
    
    const RANGE_DATE = "date";
    const DIRECTION_FORWARD = "forward";
    const DIRECTION_BACKWARD = "backword";
    
    const RANGE_DATE_YEAR = "YEAR";
    const RANGE_DATE_MONTH = "MONTH";
    const RANGE_DATE_DAY = "DAY";
    
    const NOW = "NOW";
    
    /**
     * Initialize sections
     */
    public function __construct()
    {
        $this->_query = "";
        $this->_facets = array();
        $this->_ranges = array();
        $this->_filters = array();
        $this->_networks = array();
        
        $this->_numPerPage = 25;
        $this->_page = 1;
        
    }
    
    /**
     * Set the main query
     * 
     * @param string $query The query
     * @return UpCloo_Model_Search Chain me baby
     */
    public function query($query)
    {
        $this->_query = trim($query);
        
        return $this;
    }
    
    /**
     * Set the actual page
     * 
     * @param int $page A page number
     * @return UpCloo_Model_Search
     */
    public function page($page)
    {
        $page = (int)$page;
        if (!$page) {
            throw new Exception("Page must be a numeric value");
        }
        $this->_page = $page;
        
        return $this;
    }
    
    public function numPerPage($numPerPage)
    {
        $numPerPage = (int)$numPerPage;
        if (!$numPerPage) {
            throw new Exception("Num Per Page must be a number");
        }
        $this->_numPerPage = $numPerPage;
        
        return $this;
    }
    
    /**
     * Require a network query
     * 
     * @param string $network A your network partner sitekey that you
     * want to involve in your query
     * @return UpCloo_Model_Search Chain me please...
     */
    public function network($network)
    {
        $this->_networks[] = $network;
        
        return $this;
    }
    
    /**
     * Require a new facet query 
     * 
     * @param string $facet The field that you want facet
     * @return UpCloo_Model_Search Chain me please...
     */
    public function facet($facet)
    {
        $this->_facets[] = $facet;
        
        return $this;
    }
    
    /**
     * Add a filter by to this query
     * 
     * @param string $field The field to filter
     * @param string $value The value of this filter
     * @return UpCloo_Model_Search Chain me please...
     */
    public function filterBy($field, $value)
    {
        $filter = new stdClass();
        $filter->by = $field;
        $filter->value = $value;
        
        $this->_filters[] = $filter;
        
        return $this;
    }
    
    /**
     * Add a range group query 
     * 
     * @param string $type Type of range
     * @param string $field The field to group and range
     * @param mixed $gap Gap of range query
     * @param string $direction A direction of group
     * @param mixed $from Start from
     * @param mixed $to Ends to
     * @param mixed $value The value of range (tipically is YEAR/MONTH on dates)
     * 
     * @return UpCloo_Model_Search Chain me please...
     */
    public function range($type=self::RANGE_DATE, 
            $field="publish_date", 
            $gap="1", 
            $direction=self::DIRECTION_FORWARD, 
            $from="1900-01-01T00:00:00Z", 
            $to=self::NOW, 
            $value=self::RANGE_DATE_YEAR)
    {
        $range = new stdClass();
        $range->type = $type;
        $range->field = $field;
        $range->gap = $gap;
        $range->direction = $direction;
        $range->from = $from;
        $range->to = $to;
        $range->value = $value;
        
        return $this;
    }
    
    /**
     * String representation of this model
     * 
     * @return string The XML representation
     */
    public function __toString()
    {
        return $this->asXml();
    }
    
    /**
     * Convert as XML string
     * 
     * @throws Exception In case of errors
     * @return string The xml model
     */
    public function asXml()
    {
        if (empty($this->_query)) {
            throw new Exception("You must set the main query");
        }
        
        $manager = UpCloo_Manager::getInstance();
        
        $document = new DOMDocument("1.0", "utf-8");
        $model = $document->createElement("model");
        
        $searchNode = $document->createElement("search");
        $searchNode->setAttribute("sitekey", $manager->getSiteKey());
        $searchNode->setAttribute("password", $manager->getPassword());
        
        if ($this->_page) {
            $searchNode->setAttribute("page", $this->_page);
        }
        
        if ($this->_numPerPage) {
            $searchNode->setAttribute("numPerPage", $this->_numPerPage);
        }

        $q = $document->createElement("q", $this->_query);
        $searchNode->appendChild($q);
        
        //Facet section
        if (count($this->_facets) > 0) {
            foreach ($this->_facets as $facet) {
                $facet = $document->createElement("facet", $facet);
                $searchNode->appendChild($facet);
            }
        }
        
        if (count($this->_ranges) > 0) {
            foreach ($this->_ranges as $range) {
                $rangeNode = $document->createElement("range", $range->value);
                $rangeNode->setAttribute("type", $range->type);
                $rangeNode->setAttribute("field", $range->field);
                $rangeNode->setAttribute("gap", $range->gap);
                $rangeNode->setAttribute("direction", $range->direction);
                $rangeNode->setAttribute("from", $range->from);
                $rangeNode->setAttribute("to", $range->to);
                
                $searchNode->appendChild($rangeNode);
            }
        }
        
        //Filters section
        if (count($this->_filters) > 0) {
            foreach ($this->_filters as $filter) {
                $filterNode = $document->createElement("filter", $filter->value);
                $filterNode->setAttribute("by", $filter->by);
                $searchNode->appendChild($filterNode);
            }
        }
        
        //Network section
        if (count($this->_networks) > 0) {
            $networkNode = $document->createElement("network");
            foreach ($this->_networks as $network) {
                $sitekey = $document->createElement("sitekey", $network);
                $networkNode->appendChild($sitekey);
            }
            $searchNode->appendChild($networkNode);
        }
        
        $model->appendChild($searchNode);
        $document->appendChild($model);
        
        return $document->saveXML();
    }
}