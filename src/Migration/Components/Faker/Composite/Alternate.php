<?php
namespace Migration\Components\Faker\Composite;

use Migration\Components\Faker\Exception as FakerException;
use Migration\Components\Faker\Formatter\FormatEvents;
use Migration\Components\Faker\Formatter\GenerateEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
  *  Allows many datatypes to be used based on
  *  alternate pattern.
  *
  *  @example
  *
  * formats xxxx |xxx-xx | xx-xxxxx
  * step = 1 
  *
  * Each call to generate will generate once and switch to the next
  * format until format list is exhausted where repeat.
  *
  * formats xxxx |xxx-xx | xx-xxxxx
  * step = 100 
  *
  * Each call to generate will same format 100 times and switch to the next
  * format until format list is exhausted where repeat.
  * 
  *
  */
class Alternate implements CompositeInterface
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
     * @var integer the step value  
     */
    protected $step = 1; 
    
    /**
      *  @var Symfony\Component\EventDispatcher\EventDispatcherInterface
      */
    protected $event;
    
    /**
      *  Class construtor
      *
      *  @access public
      *  @return void
      *  @param string $id the schema name
      *  @param Table $parent 
      */
    public function __construct($id, Table $parent, EventDispatcherInterface $event,$step =1)
    {
        $this->id = $id;
        $this->setParent($parent);
        $this->event = $event;
        
        if(is_integer($step) === false) {
            throw new FakerException('Step must be an integer');
        }
        
        if($step <= 0) {
           throw new FakerException('Step must be greater than 0');
        }
        
        $this->step = $step;
        
    }
    
    /**
      *  @inheritdoc 
      */
    public function generate($rows,$values = array())
    {
        $number_types = count($this->child_types);
        $use = 0;

        # find which child should be used
        # first row is 1 no zero
        foreach($this->child_types as $index => $type) {
            if($rows <= $this->step * ($index+1)) {
                return $type->generate($rows,$values);
                break;
            }
        }
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
       ''
    }
    
    //  -------------------------------------------------------------------------
}
/* End of File */