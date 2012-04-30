<?php
require_once __DIR__ .'/../base/AbstractProject.php';

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Migration\Components\Writer\WriterInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;

use  Migration\Components\Faker\Formatter\FormatterFactory;

class FakerFormatterFactoryTest extends AbstractProject
{
    
    public function testFactoryConstructor()
    {
        $event    = $this->getMockBuilder('\Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $writer_manager   = $this->getMockBuilder('\Migration\Components\Writer\Manager')
                         ->disableOriginalConstructor()
                         ->getMock();
        
        $factory = new FormatterFactory($event,$writer_manager);
        
        $this->assertInstanceOf('Migration\Components\Faker\Formatter\FormatterFactory',$factory);
        
    }
    
    
    public function testCreate()
    {
       
        $formatter_full = '\\Migration\\Components\\Faker\\Formatter\\Sql';
        $formatter_key = 'Sql';
        
        $event    = $this->getMockBuilder('\\Symfony\\Component\\EventDispatcher\\EventDispatcherInterface')->getMock();
        $event->expects($this->once())
              ->method('addSubscriber');
  
        $writer_instance = $this->getMockBuilder('\Migration\Components\Writer\WriterInterface')->getMock();
        
        
        $writer_manager   = $this->getMockBuilder('\Migration\Components\Writer\Manager')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $writer_manager->expects($this->once())
               ->method('getWriter')
               ->with($this->equalTo('mysql'))
               ->will($this->returnValue($writer_instance));
                   
                        
        $platform = $this->getMockBuilder('\Doctrine\DBAL\Platforms\AbstractPlatform')
                         ->getMockForAbstractClass();
        
        $platform->expects($this->once())
                  ->method('getName')
                  ->will($this->returnValue('mysql'));
        
        
        $factory = new FormatterFactory($event,$writer_manager);
        
        $this->assertInstanceOf($formatter_full,$factory->create($formatter_key,$platform));
        
    }
    
     
    public function testPhpunitFormatterCreate()
    {
        $formatter_full = '\\Migration\\Components\\Faker\\Formatter\\Phpunit';
        $formatter_key = 'Phpunit';
        
        $event    = $this->getMockBuilder('\Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $event->expects($this->once())
              ->method('addSubscriber');
        
        $writer_instance = $this->getMockBuilder('\Migration\Components\Writer\WriterInterface')->getMock();
        
        
        $writer_manager   = $this->getMockBuilder('\Migration\Components\Writer\Manager')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $writer_manager->expects($this->once())
               ->method('getWriter')
               ->with($this->equalTo('mysql'))
               ->will($this->returnValue($writer_instance));
       
    
        $platform = $this->getMockBuilder('\Doctrine\DBAL\Platforms\AbstractPlatform')
                         ->getMockForAbstractClass();
        
        $platform->expects($this->once())
                  ->method('getName')
                  ->will($this->returnValue('mysql'));
        
        
        $factory = new FormatterFactory($event,$writer_manager);
        
        $this->assertInstanceOf($formatter_full,$factory->create($formatter_key,$platform));
        
    }
    
    
    public function testCreateLowercaseKey()
    {
        $formatter_full = '\\Migration\\Components\\Faker\\Formatter\\phpunit';
        $formatter_key = 'phpunit';
        
        $event    = $this->getMockBuilder('\Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $event->expects($this->once())
              ->method('addSubscriber');
        
        
        $writer_instance = $this->getMockBuilder('\Migration\Components\Writer\WriterInterface')->getMock();
        
        
        $writer_manager   = $this->getMockBuilder('\Migration\Components\Writer\Manager')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $writer_manager->expects($this->once())
               ->method('getWriter')
               ->with($this->equalTo('mysql'))
               ->will($this->returnValue($writer_instance));
       
        
        $platform = $this->getMockBuilder('\Doctrine\DBAL\Platforms\AbstractPlatform')
                         ->getMockForAbstractClass();
        
        $platform->expects($this->once())
                  ->method('getName')
                  ->will($this->returnValue('mysql'));
        
        
        $factory = new FormatterFactory($event,$writer_manager);
        
        $this->assertInstanceOf($formatter_full,$factory->create($formatter_key,$platform));
        
    }
    
    /**
      *  @expectedException Migration\Components\Faker\Exception 
      */
    public function testCreateBadKey()
    {
        $formatter_key = 'bad_key';
        
        $event    = $this->getMockBuilder('\Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $writer_manager   = $this->getMockBuilder('\Migration\Components\Writer\Manager')
                        ->disableOriginalConstructor()
                        ->getMock();
                        
        $platform = $this->getMockBuilder('\Doctrine\DBAL\Platforms\AbstractPlatform')
                         ->getMockForAbstractClass();
        
        $factory = new FormatterFactory($event,$writer_manager);
        
        $factory->create($formatter_key,$platform);
        
    }
    
   
    
}
/* End of File */