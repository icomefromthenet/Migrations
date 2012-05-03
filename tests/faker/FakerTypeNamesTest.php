<?php
require_once __DIR__ .'/../base/AbstractProject.php';

use \Migration\Components\Faker\Type\Names;

class FakerTypeNamesTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();

        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
      
            
        $type = new Names($id,$parent,$event,$utilities);
        
        $this->assertInstanceOf('\\Migration\\Components\\Faker\\TypeInterface',$type);
    
    }
    
    //--------------------------------------------------------------------------
    
    public function testConfig()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new Names($id,$parent,$event,$utilities);
        $config = array('format' =>'xxxx'); 
        
        $options = $type->merge($config);        
        
        $this->assertEquals($options['format'],$config['format']);
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Migration\Components\Faker\Exception
      *  @expectedExceptionMessage The child node "format" at path "config" must be configured
      */
    public function testConfigMissingFormat()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new Names($id,$parent,$event,$utilities);
        $config = array(); 
        
        $options = $type->merge($config);        
        
        
    }
    
    //  -------------------------------------------------------------------------
    
    
    public function testGenerate()
    {
        $id = 'table_two';
        $project = $this->getProject();
        $utilities = new Migration\Components\Faker\Utilities($project);
                          
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new Names($id,$parent,$event,$utilities);
        $type->setOption('format','{fname} {lname}');
        $type->validate(); 
         
        $this->assertStringMatchesFormat('%s %s',$type->generate(1,array()));
    
    
        $type->setOption('format','{fname} {inital} {lname}');
        $type->validate(); 
         
        $this->assertStringMatchesFormat('%s %s %s',$type->generate(1,array()));
    
        $type->setOption('format','{fname},{inital} {lname}');
        $type->validate(); 
         
        $this->assertStringMatchesFormat('%s,%s %s',$type->generate(1,array()));
        
        $type->setOption('format','{fname},{lname} {inital}');
        $type->validate(); 
         
        $this->assertStringMatchesFormat('%s,%s %s',$type->generate(1,array()));
        
        $type->setOption('format','{lname}');
        $type->validate(); 
         
        $this->assertStringMatchesFormat('%s',$type->generate(1,array()));
        
        $type->setOption('format','{fname},{lname} {inital}');
        $type->validate(); 
         
    }
    
}
/*End of file */
