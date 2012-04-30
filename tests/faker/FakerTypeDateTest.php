<?php
require_once __DIR__ .'/../base/AbstractProject.php';

use \Migration\Components\Faker\Type\Date;

class FakerTypeDateTest extends AbstractProject
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
      
            
        $type = new Date($id,$parent,$event,$utilities);
        
        $this->assertInstanceOf('\\Migration\\Components\\Faker\\TypeInterface',$type);
    
    }
    
    //--------------------------------------------------------------------------
    
    public function testDefaultConfig()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new Date($id,$parent,$event,$utilities);
        $config = array(
                        'start'=> '14-01-1983',
                        ); 
        
        $options = $type->merge($config);        
        
        $this->assertInstanceOf('\DateTime',$options['start']);
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Unrecognized options "aaaa" under "config"
      */
    public function testConfigBadValue()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new Date($id,$parent,$event,$utilities);
        $config = array('aaaa' => 'bbb'); 
        
        $options = $type->merge($config);        
        
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Invalid configuration for path "config.start": DateTime::__construct(): Failed to parse time string (bbb) at position 0 (b): The timezone could not be found in the database 
      */
    public function testStartInvalid()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new Date($id,$parent,$event,$utilities);
        $config = array('start' => 'bbb'); 
        
        $options = $type->merge($config);        
        
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Invalid configuration for path "config.max": DateTime::__construct(): Failed to parse time string (bbb) at position 0 (b): The timezone could not be found in the database 
      */
    public function testMaxInvalid()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new Date($id,$parent,$event,$utilities);
        $config = array('max' => 'bbb','start' =>'1st August 2007'); 
        
        $options = $type->merge($config);        
        
        
    }
        
    //  -------------------------------------------------------------------------
    
    
    public function testGenerate()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new Date($id,$parent,$event,$utilities);
        
        # test with start > 0
        $type->setOption('start','today');
        $type->setOption('modify','+ 1 hour');
        $type->validate(); 
         
        $this->assertInstanceOf('\DateTime',$type->generate(1,array()));
        $this->assertInstanceOf('\DateTime',$type->generate(1,array()));
      
    
        # test with max
        $type->setOption('start','today');
        $type->setOption('modify','+ 1 hour');
        $type->setOption('max','today +3 hours');
        $type->validate(); 
        
       $dte1 = $type->generate(1,array());
       $dte2 = $type->generate(2,array());
       $dte3 = $type->generate(3,array());
       $dte4 = $type->generate(4,array());
       $dte5 = $type->generate(4,array());
      
       # test if date has been reset once max reached
       $this->assertEquals($dte1->format('U'),$dte5->format('U'));
       
       # iterations are not equal ie modify is appied on each loop
       $this->assertFalse($dte1->format('U') === $dte2->format('U'));
       $this->assertFalse($dte2->format('U') === $dte3->format('U')); 
       $this->assertFalse($dte3->format('U') === $dte4->format('U')); 
       $this->assertFalse($dte4->format('U') === $dte5->format('U')); 
   
    }
    
    
    
}
/*End of file */
