<?php
namespace Migration\Tests;

use Migration\Tests\Base\AbstractProject;
use Migration\ChannelEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;


class ChannelDispatcherTest extends AbstractProject
{
    
    public function testDefaultChannel()
    {
        $project = $this->getProject();


        $defaultChannel  = $this->getMockBuilder('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface')->getMock();

        $dispatcher = new ChannelEventDispatcher($defaultChannel);
        
        $this->assertEquals($defaultChannel,$dispatcher->getDefaultChannel());
        $this->assertEquals($defaultChannel,$dispatcher->switchChannel());
        
        
    
    }
    
    public function testAddExtraChannel()
    {
        $project = $this->getProject();
        $defaultChannel  = $this->getMockBuilder('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface')->getMock();
        $extraChannel  = $this->getMockBuilder('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface')->getMock();
        $channelName = 'mychannel';
        
        $dispatcher = new ChannelEventDispatcher($defaultChannel);
        
        $this->assertFalse($dispatcher->hasChannel($channelName));
        
        $dispatcher->addChannel($channelName,$extraChannel);
        
        $this->assertTrue($dispatcher->hasChannel($channelName));
        
        $this->assertEquals(array('mychannel' => $extraChannel),$dispatcher->getExtraChannels());
        
    }
    
    /**
     * @expectedException Migration\Exception
     * @expectedExceptionMessage mychannel has been created already
     */ 
    public function testAddExtraChannelFailsWhenDuplicate()
    {
        $project = $this->getProject();
        $defaultChannel  = $this->getMockBuilder('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface')->getMock();
        $extraChannel  = $this->getMockBuilder('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface')->getMock();
        $channelName = 'mychannel';
        
        $dispatcher = new ChannelEventDispatcher($defaultChannel);
        
        $dispatcher->addChannel($channelName,$extraChannel);
        $dispatcher->addChannel($channelName,$extraChannel);
        
    }
    
    /**
     * @expectedException Migration\Exception
     * @expectedExceptionMessage mychannelBAD has not been added
     */ 
    public function testSwitchChannelFailsWhenNotExist()
    {
        $project = $this->getProject();
        $defaultChannel  = $this->getMockBuilder('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface')->getMock();
        $extraChannel  = $this->getMockBuilder('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface')->getMock();
        $channelName = 'mychannel';
        
        $dispatcher = new ChannelEventDispatcher($defaultChannel);
        
        $dispatcher->addChannel($channelName,$extraChannel);
        $dispatcher->switchChannel($channelName.'BAD');
        
    }
    
    public function testSwitchChannel()
    {
        $project = $this->getProject();
        $defaultChannel  = $this->getMockBuilder('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface')->getMock();
        $extraChannelA  = $this->getMockBuilder('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface')->getMock();
        $extraChannelB  = $this->getMockBuilder('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface')->getMock();
        $channelName = 'mychannel';
        
        $dispatcher = new ChannelEventDispatcher($defaultChannel);
        
        $dispatcher->addChannel($channelName.'A',$extraChannelA);
        $dispatcher->addChannel($channelName.'B',$extraChannelB);
        $dispatcher->switchChannel($channelName.'A');
        
        $this->assertEquals($extraChannelB,$dispatcher->getActiviteChannel());
        
    }
    
    public function testEventDisaptchBothDefaultAndActive()
    {
        $project        = $this->getProject();
        $that           = $this;
        $defaultChannel = new EventDispatcher();
        $extraChannel   = new EventDispatcher();
        
        $testEventName  = 'tevent';
        $dispatcher     = new ChannelEventDispatcher($defaultChannel);
        $hasBeenCalledChannel = false;
        $hasBeenCalledDefault = false;
        
        $dispatcher->addChannel('mychannel',$extraChannel);
        $dispatcher->switchChannel('mychannel');
        
        
        $defaultChannel->addListener($testEventName,function() use (&$hasBeenCalledDefault){
            $hasBeenCalledDefault = true;
        });
        
        $extraChannel->addListener($testEventName,function() use (&$hasBeenCalledChannel){
            $hasBeenCalledChannel= true;
        });
        
        
        $dispatcher->dispatch($testEventName);
        
        $this->assertTrue($hasBeenCalledDefault,'event not dispatched to default channel');
        $this->assertTrue($hasBeenCalledChannel,'event not dispatched to extra channel');
   
    }
    
    public function testEventDisaptchNotDoubled()
    {
        $project        = $this->getProject();
        $that           = $this;
        $defaultChannel = new EventDispatcher();
        
        $testEventName  = 'tevent';
        $dispatcher     = new ChannelEventDispatcher($defaultChannel);
        $hasBeenCalledDefault = 0;
        
        $defaultChannel->addListener($testEventName,function() use (&$hasBeenCalledDefault){
            $hasBeenCalledDefault += 1;
        });
        
        
        $dispatcher->dispatch($testEventName);
        
        $this->assertEquals(1,$hasBeenCalledDefault,'event was dispatched more than once to default channel');
        
    }
}
/* End of File */