<?php

require_once __DIR__ .'/../base/AbstractProject.php';

use Migration\ColumnTypeFactory;

class DoctrineColumnTypeFactoryTest extends AbstractProject
{

    public function testFactoryCreate()
    {
        $factory = new ColumnTypeFactory();
        $project = $this->getProject();
        
        $this->assertInstanceOf('Doctrine\DBAL\Types\ArrayType',$factory->create('array'));
        
    }

    
    /**
      *  @expectedException Migration\Components\Faker\Exception 
      */
    public function testFactoryCreateBadKey()
    {
        $factory = new ColumnTypeFactory();
        $factory->create('badkey');
    }
    
    /**
      *  @expectedException Migration\Components\Faker\Exception 
      */
    public function testregisterExtension()
    {
        ColumnTypeFactory::registerExtension('mytype','Migration\\Components\\Extension\\Doctine\\Type\\MyType');
        $factory = new ColumnTypeFactory();
        $this->assertTrue(true);
        
        $factory->create('mytype');
    }
    
    /**
      *  @expectedException Migration\Components\Faker\Exception 
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