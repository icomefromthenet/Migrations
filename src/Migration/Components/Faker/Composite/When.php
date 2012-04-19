<?php
namespace Migration\Components\Faker\Composite;

use Migration\Components\Faker\Exception as FakerException;
use Migration\Components\Faker\Formatter\FormatEvents;
use Migration\Components\Faker\Formatter\GenerateEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
  *  Child to Switch
  *
  *  need to specify at which row to switch
  */
class When implements CompositeInterface
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
      *  @var Doctrine\DBAL\Types\Type the mapper to convert php types into database representations
      */
    protected $column_type;
    
    /**
      *  @var Symfony\Component\EventDispatcher\EventDispatcherInterface
      */
    protected $event;
    
    /**
      *  @var integer the row number to swap at 
      */
    protected $swap;
    
    /**
      *  Class construtor
      *
      *  @access public
      *  @return void
      *  @param string $id the schema name
      *  @param CompositeInterface $parent
      *  @param EventDispatcherInterface $event
      *  @param integer $swap
      */
    public function __construct($id, CompositeInterface $parent, EventDispatcherInterface $event,$swap)
    {
        $this->id = $id;
        $this->setParent($parent);
        $this->event = $event;
        
        if(is_integer($swap) === false) {
            throw new FakerException('Swap-When must be an integer');
        }
        
        if($swap <= 0) {
           throw new FakerException('Swap-When must be greater than 0');
        }
        
        $this->swap = $swap;
    }
    
    /**
      *  @inheritdoc 
      */
    public function generate($rows,$values = array())
    {
        if(isset($this->child_types[0]) === false) {
            throw new FakerException('Switch has not been given a type to use');
        }
        
        return $this->child_types[0]->generate($rows,$values);
    }
    
    
    //  -------------------------------------------------------------------------
    
    public function getSwap()
    {
        return $this->swap;
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
          throw new FakerException('When must have at least 1 type');
        }

        return true;      
        
    }

    //  -------------------------------------------------------------------------
}
/* End of File */