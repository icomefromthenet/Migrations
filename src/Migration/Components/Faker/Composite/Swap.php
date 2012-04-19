<?php
namespace Migration\Components\Faker\Composite;

use Migration\Components\Faker\Exception as FakerException;
use Migration\Components\Faker\Formatter\FormatEvents;
use Migration\Components\Faker\Formatter\GenerateEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


class Swap implements CompositeInterface
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
      *  @var Symfony\Component\EventDispatcher\EventDispatcherInterface
      */
    protected $event;

    /**
      *  @var integer[] the ranges to swap from  
      */
    protected $switch_map = null;    
    
    
    /**
      *  Class construtor
      *
      *  @access public
      *  @return void
      *  @param string $id the schema name
      *  @param CompositeInterface $parent
      *  @param EventDispatcherInterface $event
      */
    public function __construct($id, CompositeInterface $parent, EventDispatcherInterface $event)
    {
        $this->id = $id;
        $this->setParent($parent);
        $this->event = $event;
        
    }
    
    //  -------------------------------------------------------------------------
    
    
    public function generate($rows,$values = array())
    {
       $high = 0;
       $low = 0;
       $i = 0;
       $use_index = 0;
       $key_map = array();
       $value_map = array();
      
       # test if we have a range map
       
       if($this->switch_map === null) {
            
            foreach($this->child_types as $index => $type) {
                $this->switch_map[$index] = (integer) $type->getSwap();
            } 
       
            # low to high quicksort (maintains key associations)
       
            asort($this->switch_map,SORT_NUMERIC);
       }
       
       $key_map = array_keys($this->switch_map);
       $value_map = array_values($this->switch_map);
       
       # find which child should be using for the current row

       $last = count($value_map) -1;
       $i = 0;

       for($i; $i <= $last; $i++) {
        
            # if we no other ranges check then use the last.
            if($i === $last) {
                $use_index = $key_map[$i];
                break;
            }
        
            $high = $value_map[$i+1];
            $low = $value_map[$i];
        
            if($rows <= $high AND $rows  >= $low ) {
                $use_index = $key_map[$i];        
                break;
            }
       }
       
       return $this->child_types[$use_index]->generate($rows,$values);
    }
    
    //  -------------------------------------------------------------------------
    
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
      *  Get Event Dispatcher
      *
      *  @return Symfony\Component\EventDispatcher\EventDispatcherInterface 
      */ 
    public function getEventDispatcher()
    {
        return $this->event;
    }

    
    public function toXml()
    {
        return '';
    }
    
    //  -------------------------------------------------------------------------
    
    public function validate()
    {
       # ask children to validate themselves
        
        foreach($this->getChildren() as $child) {
        
          $child->validate(); 
        }
        
        # check that children have been added
        
        if(count($this->getChildren()) === 0) {
          throw new FakerException('Swap must have at least 1 when');
        }

        return true;       
        
    }
    
    //  -------------------------------------------------------------------------
}
/* End of File */