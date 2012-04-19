<?php
namespace Migration\Components\Faker\Composite;

use Migration\Components\Faker\TypeInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface CompositeInterface extends TypeInterface
{

    /**
      *  Fetches the parent in this type composite
      *
      *  @return Migration\Components\Faker\Composite\CompositeInterface
      *  @access public
      */
    public function getParent();

    /**
      *  Sets the parent of this type composite
      *
      *  @access public
      *  @param Migration\Components\Faker\Composite\CompositeInterface $parent;
      */
    public function setParent(CompositeInterface $parent);
    
    
    /**
      *   Fetches the children of this type composite
      *
      *   @access public
      *   @return Migration\Components\Faker\Composite\CompositeInterface[] 
      */
    public function getChildren();
    
    
    /**
      *  Add's a child to this type composite
      *
      *  @param Migration\Components\Faker\Composite\CompositeInterface $child
      */
    public function addChild(CompositeInterface $child);
    
    
    /**
      *  Get Event Dispatcher
      *
      *  @return Symfony\Component\EventDispatcher\EventDispatcherInterface 
      */ 
    public function getEventDispatcher();
    
    /**
      *  Convert the composite to xml
      *
      *  @return string of xml
      */
    public function toXml();
    
    /**
      *  Checks that each composite is in valid state
      *
      *  @return boolean
      *  @access public
      *  @throws Migration\Components\Faker\Exception
      */
    public function validate();
    
}
/* End of File */