<?php
namespace Migration\Components\Faker\Composite;

use Migration\Components\Faker\Exception as FakerException;
use Migration\Components\Faker\Formatter\FormatEvents;
use Migration\Components\Faker\Formatter\GenerateEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Migration\Components\Faker\Composite\CompositeInterface;


class Table implements CompositeInterface
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
      *  @var number of rows to generate 
      */
    protected $rows;
    
    
    /**
      *  Class construtor
      *
      *  @access public
      *  @return void
      *  @param string $id the schema name
      *  @param CompositeInterface $parent
      *  @param EventDispatcherInterface $event
      *  @param integer the number of rows to generate
      */
    public function __construct($id, CompositeInterface $parent, EventDispatcherInterface $event,$rows)
    {
        $this->id = $id;
        $this->setParent($parent);
        $this->event = $event;
        
        # set the rows to generate
        
        if(is_integer($rows) === false) {
            throw new FakerException('Table Type must have rows to generate as an integer');
        }
        
        $this->rows = $rows;
        
    }
   
    /**
      *  @inheritdoc 
      */
    public function generate($rows,$values = array())
    {
        # dispatch the start table event
       
        $this->event->dispatch(
                FormatEvents::onTableStart,
                new GenerateEvent($this,$values,$this->getId())
        );
   
   
        do {
                
                # reset values for next row run.
                
                $values = array();
                
                # dispatch the row start event
            
                $this->event->dispatch(
                    FormatEvents::onRowStart,
                    new GenerateEvent($this,$values,$this->getId())
                );

                # send the generate event to the columns
       
                foreach($this->child_types as $type) {
                    $values = $type->generate($rows,$values);            
                }
        
                
                # dispatch the row stop event
                
                $this->event->dispatch(
                    FormatEvents::onRowEnd,
                    new GenerateEvent($this,$values,$this->getId())
                );

                    
                # increment the rows needed by datatypes. 
                $rows = $rows +1;
        }
        while($rows <= $this->rows);
        
        
        # dispatch the stop table event
        
        $this->event->dispatch(
                    FormatEvents::onTableEnd,
                    new GenerateEvent($this,$values,$this->getId())
        );
        
        return null;
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
      *  @inheritdoc
      */
    public function getEventDispatcher()
    {
          return $this->event;
    }
    
    
    public function toXml()
    {
        $str = sprintf('<table name="%s" generate="0">',$this->getId()). PHP_EOL;
     
        foreach($this->child_types as $child) {
               $str .= $child->toXml();     
        }
     
        $str .= '</table>' . PHP_EOL;
      
        return $str;
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
          throw new FakerException('Table must have at least 1 column');
        }

        return true;  
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  Return the number of rows this table will generate
      *
      *  @access public
      *  @return integer the rows to generate
      */
    public function getToGenerate()
    {
        return $this->rows;
    }
    
    //  -------------------------------------------------------------------------
}
/* End of File */