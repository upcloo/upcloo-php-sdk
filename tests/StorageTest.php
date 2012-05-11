<?php 
class StorageTest
    extends PHPUnit_Framework_TestCase
{
    private $_pdo;
    
    public function setUp()
    {
        parent::setUp();

        $this->_pdo = new PDO("sqlite::memory:");
        $this->_pdo->exec("DROP TABLE " . UpCloo_Manager::STORAGE_NAME);
        UpCloo_Manager::getInstance()->useStorage($this->_pdo);
    }
    
    public function testBaseFunctionality()
    {
        $manager = UpCloo_Manager::getInstance();
        $manager->setCredential("username", "sitekey", "password");
        
        $stub = $this->getMock("UpCloo_Client_UpCloo", array('index'));
        
        $stub->expects($this->any())
            ->method('index')
            ->will($this->onConsecutiveCalls(true, false));
        
        $manager->setClient($stub);
        
        $ret = $manager->index(
            array(
                'id' => 'first',
                'title' => "example"        
            )
        );
        
        //It should be true!
        $ret2 = $manager->index(
                array(
                        'id' => 'first',
                        'title' => 'example'
                )
        );
        
        $this->assertTrue($ret);
        $this->assertTrue($ret2);
        
        $storage = UpCloo_Manager::getInstance()->getStorage();
        
        $query = "SELECT count(*) FROM " . UpCloo_Manager::STORAGE_NAME;
        $stmt = $storage->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        
        $this->assertEquals(1, $result);
    }
}