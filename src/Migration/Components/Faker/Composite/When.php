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
    
    protected $swtich;
    
    /**
      *  Class construtor
      *
      *  @access public
      *  @return void
      *  @param string $id the schema name
      *  @param Table $parent 
      */
    public function __construct($id, Table $parent, EventDispatcherInterface $event,$switch)
    {
        $this->id = $id;
        $this->setParent($parent);
        $this->event = $event;
        
        if(is_integer($switch) === false) {
            throw new FakerException('Switch-When must be an integer');
        }
        
        if($switch <= 0) {
           throw new FakerException('Switch-When must be greater than 0');
        }
        
        $this->swtich = $switch;
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
    
    public function useMe($rows)
    {
        # switch at 100 will return false at 101 but true at 100 
        return ($rows <= $this->swtich) ? true : false
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