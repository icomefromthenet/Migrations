<?php
require_once __DIR__ .'/../base/AbstractProject.php';

use Migration\Components\Faker\Composite\CompositeInterface; 
use Migration\Components\Faker\Type\Type;
use Migration\Components\Faker\TypeFactory;

class FakerTypeFactoryTest extends AbstractProject
{
    
    public function tearDown()
    {
        TypeFactory::clearExtensions();
        
        parent::tearDown();
    }
    
    //  -------------------------------------------------------------------------
        
    public function testRegisterExtension()
    {
        $mock = $this->getMockBuilder('Migration\Components\Faker\Type\Type')
                    ->setMockClassName('MockTypeTest')
                    ->disableOriginalConstructor()
                    ->getMockForAbstractClass();   

        TypeFactory::registerExtensions(array('mocka' => 'MockTypeTest'));  
    
        $this->assertTrue(true);
    }

    //  -------------------------------------------------------------------------
    
    public function testANewFactory() 
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                        ->disableOriginalConstructor()
                        ->getMock();

        $this->assertInstanceOf('Migration\Components\Faker\TypeFactory',new TypeFactory($utilities,$event));        
    }
    
    //  -------------------------------------------------------------------------
    
    public function testCreateType()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        
        $mock = $this->getMockBuilder('Migration\Components\Faker\Type\Type')
                    ->setMockClassName('MockTypeTestB')
                    ->disableOriginalConstructor()
                    ->getMockForAbstractClass();   

        TypeFactory::registerExtensions(array('mockb' => 'MockTypeTestB'));  
    
        $typefactory = new TypeFactory($utilities,$event);
        
        $type = $typefactory->create('mockb',$parent);
        
        $this->assertInstanceOf('MockTypeTestB',$type);
    }
    
    //  -------------------------------------------------------------------------

    /**
      *  @expectedException Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Class not found at::MockTypeTestCa
      */
    public function testCreateTypeExceptionNotExist()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        
        $mock = $this->getMockBuilder('Migration\Components\Faker\Type\Type')
                    ->setMockClassName('MockTypeTestC')
                    ->disableOriginalConstructor()
                    ->getMockForAbstractClass();   

        TypeFactory::registerExtensions(array('mockb' => 'MockTypeTestCa'));  
    
        $typefactory = new TypeFactory($utilities,$event);
        
        $type = $typefactory->create('mockb',$parent);
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Type not found at::mockda
      */
    public function testBadClassKeyException()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        
        $mock = $this->getMockBuilder('Migration\Components\Faker\Type\Type')
                    ->setMockClassName('MockTypeTestD')
                    ->disableOriginalConstructor()
                    ->getMockForAbstractClass();   

        TypeFactory::registerExtensions(array('mockb' => 'MockTypeTestD'));  
    
        $typefactory = new TypeFactory($utilities,$event);
        
        $type = $typefactory->create('mockda',$parent);
        
    }
    
    //  -------------------------------------------------------------------------
}
/* End of File */