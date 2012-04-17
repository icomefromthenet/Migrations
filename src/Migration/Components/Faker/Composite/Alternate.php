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
      *  @param CompositeInterface $parent
      *  @param EventDispatcherInterface $event
      *  @param intger $step
      */
    public function __construct($id, CompositeInterface $parent, EventDispatcherInterface $event,$step =1)
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
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @var the current index 
      */
    protected $current = 0;
    
    /**
      *  @var the current step value 
      */
    protected $current_step = 0;
    
    /**
      *  @inheritdoc 
      */
    public function generate($rows,$values = array())
    {
        # set alternate loop counter if at 0
        if($this->current_step === 0) {
            $this->current_step = $this->step;
        }
        
        # first row is 1 no zero
        $value = $this->child_types[$this->current]->generate($rows,$values);
        
        # deincrement the loop
        $this->current_step = $this->current_step -1;
        
        # have we reached the end of the current step
        if($this->current_step === 0) {
            
            # yes alternate the current index to next child
            $this->current++; 
            
            # are we at the end of the list?
            if(($this->current) >= count($this->child_types)) {
                $this->current = 0;
            }
        }
        
        return $value;
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
}
/* End of File */