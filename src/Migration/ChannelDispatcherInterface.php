<?php
namespace Migration;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Sublets events into channels that can be swapped.
 * 
 * As I wanted to support multiple schemas in single project I needed a 
 * way to namespace events as this was a late addition I already had used the basic symfony2 event disaptcher.
 * 
 * This object will proxy normal event dispatcher API calls to the currently selected channel.
 * Each schema will instance its own channel. 
 * 
 * There is also a default channel to be used for global events.
 * 
 * 
 * @since 1.5
 * @author Lewis Dyer <getintouch@icomefromthenet.com.au>
 */ 
interface ChannelDispatcherInterface extends EventDispatcherInterface
{
    
    /**
     *  Fetch the extra channels does NOT include default
     * 
     * @access public
     * @return array[EventDispatcherInterface]
    */ 
    public function getExtraChannels();

    /**
     *  Return the default channel
     *  
     *  @access public
     *  @return EventDispatcherInterface
     */ 
    public function getDefaultChannel();
    
    /**
     *  Return the active channel
     *  
     *  @access public
     *  @return EventDispatcherInterface
     */
    public function getActiviteChannel();
    
    
    /**
     * Adds a new channel
     * 
     * @access public
     * @return void
     * @param   string                    $channelName    The name to id the channel
     * @param   EventDispatcherInterface  $channel        The EventDispatcher to use
     */ 
    public function addChannel($channelName, EventDispatcherInterface $channel);

    /**
     * Check if a channel has been setup under name x
     * 
     * @access public
     * @return  boolean True if channel exists
     * @param   string  $channelName    The id of the channel to fetch.
     */ 
    public function hasChannel($channelName);
    
    /**
     * Switch to the named channel
     * 
     * @throws FakerException if the channel does not exist
     * @access public
     * @param   string  $channelName    The id to swtich too
     * @return EventDispatcherInterface The chanel object
     */ 
    public function switchChannel($chanelName = null);

    
}
/* End of Interface */