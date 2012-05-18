<?php
namespace Migration\Tests;

use Migration\ColumnTypeFactory,
    Migration\Tests\Base\AbstractProject;

class ColumnTypeFactoryTest extends AbstractProject
{

    public function testFactoryCreate()
    {
        $factory = new ColumnTypeFactory();
        $project = $this->getProject();
        
        $this->assertInstanceOf('Doctrine\DBAL\Types\ArrayType',$factory->create('array'));
        
    }

    
    /**
      *  @expectedException Migration\Exception 
      */
    public function testFactoryCreateBadKey()
    {
        $factory = new ColumnTypeFactory();
        $factory->create('badkey');
    }
    
    /**
      *  @expectedException Migration\Exception 
      */
    public function testregisterExtension()
    {
        ColumnTypeFactory::registerExtension('mytype','Migration\\Components\\Extension\\Doctine\\Type\\MyType');
        $factory = new ColumnTypeFactory();
        $this->assertTrue(true);
        
        $factory->create('mytype');
    }
    
    /**
      *  @expectedException Migration\Exception 
      */
    public function testregisterManyExtension()
    {
        
        ColumnTypeFactory::registerExtensions(array('mytype','Migration\\Components\\Extension\\Doctine\\Type\\MyType'));
        $factory = new ColumnTypeFactory();
        $this->assertTrue(true);
        
        $factory->create('mytype');
        
    }
    
}
/* End of File */