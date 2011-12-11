<?php 
class UpClooMockTest extends PHPUnit_Framework_TestCase
{
    private $_instance;
    
    public function setUp()
    {
        $this->_instance = UpCloo_Manager::getInstance();
        $this->_instance->setCredential("username", "sitekey", "password");
        $this->_instance->setClient(new UpCloo_Client_UpClooMock());
    }

    public function testMock()
    {
        $model = array(
        	'id' => 1,
            'title' => 'Using Mock'
        );
        
        $this->assertTrue($this->_instance->index($model));
    }
}
