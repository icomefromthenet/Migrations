<?php
namespace Migration\Components\Faker\Composite;

use Migration\Components\Faker\Exception as FakerException;
use Migration\Components\Faker\Formatter\FormatEvents;
use Migration\Components\Faker\Formatter\GenerateEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


class Column implements CompositeInterface
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
      *  Class construtor
      *
      *  @access public
      *  @return void
      *  @param string $id the schema name
      *  @param Table $parent 
      */
    public function __construct($id, Table $parent, EventDispatcherInterface $event)
    {
        $this->id = $id;
        $this->setParent($parent);
        $this->event = $event;
        
    }
    
    /**
      *  @inheritdoc 
      */
    public function generate($rows,$values = array())
    {
        # dispatch the start event
        
        $this->event->dispatch(
                        FormatEvents::onColumnStart,
                        new GenerateEvent($this,$values,$this->getId())
        );
        
        # send the generate command to the type
        
        foreach($this->child_types as $type) {
            $values[$this->getId()] = $value = $type->generate($rows,$values);
            
            # dispatch the generate event
            
            $this->event->dispatch(
                FormatEvents::onColumnGenerate,
                new GenerateEvent($this,array( $this->getId() => $value ),$this->getId())
            );
                        
        }
        
        # dispatch the stop event
        
        $this->event->dispatch(
                FormatEvents::onColumnEnd,
                new GenerateEvent($this,$values,$this->getId())
        );
        
        return $values;
        
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

    /**
      *  Convert the column into xml representation
      *
      *  @return string
      *  @access public
      */
    public function toXml()
    {
        $str = sprintf('<column name="%s" type="%s">',$this->getId(),$this->column_type);
        $str .= '</column>';
      
        return $str;
        
    }
    
    //  -------------------------------------------------------------------------
}
/* End of File */