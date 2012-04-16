<?php
namespace Migration\Components\Faker\Composite;

use Migration\Components\Faker\Exception as FakerException;
use Migration\Components\Faker\Formatter\FormatEvents;
use Migration\Components\Faker\Formatter\GenerateEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;



class Schema implements CompositeInterface
{
    
    /**
      *  @var CompositeInterface 
      */
    protected $parent_type;
    
    /**
      *  @var CompositeInterface[] 
      */
    protected $child_types = array();
    
    /**
      *  @var string the id of the component 
      */
    protected $id;
    
    /**
      *  @var EventDispatcherInterface 
      */
    protected $event;
    
    /**
      *  Class construtor
      *
      *  @access public
      *  @return void
      *  @param string $id the schema name
      *  @param CompositeInterface $parent (Optional for this object)
      */
    public function __construct($id , CompositeInterface $parent = null, EventDispatcherInterface $event)
    {
        $this->id = $id;
        $this->event = $event;
            
        if($parent !== null) {
            $this->setParent($parent);
        }
    }
    
    /**
      *  @inheritdoc 
      */
    public function generate($rows,$values = array())
    {
          # dispatch the start event
       
          $this->event->dispatch(
               FormatEvents::onSchemaStart,
               new GenerateEvent($this,array(),$this->getId())
          );
        
          # send generate command to children
       
          foreach($this->child_types as $type) {
               $type->generate($rows,$values);
          }
       
          # dispatch the stop event
     
          $this->event->dispatch(
               FormatEvents::onSchemaEnd,
               new GenerateEvent($this,array(),$this->getId())
          );
    }
    
    
    /**
      *  @inheritdoc 
      */
    public function getId()
    {
        return $this->id;
    }
    
    
    /**
      * @inheritdoc
      */
    public function getParent()
    {
        return $this->parent_type;
    }

    /**
      * @inheritdoc  
      */
    public function setParent(CompositeInterface $parent)
    {
        $this->parent_type = $parent;
    }
    
    
    /**
      *  @inheritdoc
      */
    public function getChildren()
    {
        return $this->child_types;
    }
    
    
    /**
      *  @inheritdoc
      */
    public function addChild(CompositeInterface $child)
    {
        return array_push($this->child_types,$child);
    }
    
    /**
      *  @inheritdoc
      */
    public function getEventDispatcher()
    {
          return $this->event;
    }
    
    public function toXml()
    {
          $str = sprintf('<schema name="%s">',$this->getId());
     
          foreach($this->child_types as $child) {
               $str .= $child->toXml();     
          }
     
          $str .= '</schema>';
      
          return $str;
    }
    
    
}
/* End of File */