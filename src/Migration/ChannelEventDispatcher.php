<?php
namespace Migration;

use Migration\Exception as  MigrationException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Implementation of the ChannelEventDispatcher
 * 
 * @since 1.5
 * @author Lewis Dyer
 */ 
class ChannelEventDispatcher implements ChannelDispatcherInterface
{
    
    /**
     * @var array 
     */ 
    protected $extraChannels;
    
    /**
     * @var Symfony\Components\Event\EventDispatcherInterface
     */ 
    protected $defaultChannel;
    
    /**
     * @var Symfony\Components\Event\EventDispatcherInterface
     */
    protected $activieChannel;
    
    
    public function __construct(EventDispatcherInterface $defaultChannel)
    {
        $this->extraChannels  = array();
        $this->defaultChannel = $defaultChannel;
        $this->activeChannel  = $defaultChannel;
        
    }
    
    // -------------------------------------------------------------------------\
    # ChannelDispatcherInterface
    
    /**
     *  Fetch the extra channels does NOT include default
     * 
     * @access public
     * @return array[EventDispatcherInterface]
    */ 
    public function getExtraChannels()
    {
        return $this->extraChannels;
    }

    /**
     *  Return the default channel
     *  
     *  @access public
     *  @return EventDispatcherInterface
     */ 
    public function getDefaultChannel()
    {
        return $this->defaultChannel;
    }
    
    /**
     *  Return the active channel
     *  
     *  @access public
     *  @return EventDispatcherInterface
     */
    public function getActiviteChannel()
    {
        return $this->activeChannel;
    }

    /**
     * Adds a new channel
     * 
     * @access public
     * @return void
     * @param   string                    $channelName    The name to id the channel
     * @param   EventDispatcherInterface  $channel        The EventDispatcher to use
     */ 
    public function addChannel($channelName, EventDispatcherInterface $channel)
    {
        if($this->hasChannel($channelName)) {
            throw new MigrationException("$channelName has been created already");
        }
        
        $this->extraChannels[$channelName] = $channel;
    }

    /**
     * Check if a channel has been setup under name x
     * 
     * @access public
     * @return  boolean True if channel exists
     * @param   string  $channelName    The id of the channel to fetch.
     */ 
    public function hasChannel($channelName)
    {
        return isset($this->extraChannels[$channelName]);
    }
    
    /**
     * Switch to the named channel, if dont provide a channel name
     * it will assume a switch to the default channel.
     * 
     * @throws MigrationException if the channel does not exist
     * @access public
     * @param   string  $channelName    The id to swtich too
     * @return EventDispatcherInterface The chanel object
     */ 
    public function switchChannel($channelName = null)
    {
        if($channelName ===  null) {
            
            $this->activeChannel = $this->defaultChannel;
            
        } else {
        
            if(false === $this->hasChannel($channelName)) {
                throw new MigrationException("$channelName has not been added");
            }
        
            $this->activeChannel = $this->extraChannels[$channelName];
        }
        
        return $this->activeChannel;
    }
    
    //--------------------------------------------------------------------------
    # EventDispatcherChannel
    
    public function dispatch($eventName, Event $event = null)
    {
       
       # disptach the default event
       if($this->activeChannel !== $this->defaultChannel) {
         $this->defaultChannel->dispatch($eventName,$event);
       }
        
        # send event to active channel
        return $this->activeChannel->dispatch($eventName,$event); 
    }

    
    public function addListener($eventName, $listener, $priority = 0)
    {
        return $this->activeChannel->addListener($eventName, $listener, $priority);
    }

    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
       return $this->activeChannel->addSubscriber($subscriber);
    }

    
    public function removeListener($eventName, $listener)
    {
        return $this->activeChannel->removeListener($eventName,$listener);
    }

    
    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        return $this->activeChannel->removeSubscriber($subscriber);
    }

    
    public function getListeners($eventName = null)
    {
        return $this->activeChannel->getListeners($eventName);
    }

    
    public function hasListeners($eventName = null)
    {
        return $this->activeChannel->hasListeners($eventName);
    }
    
}
/* End of class */