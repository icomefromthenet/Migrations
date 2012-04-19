<?php
namespace Migration\Components\Faker\Composite;

use Migration\Components\Faker\Exception as FakerException;
use Migration\Components\Faker\Formatter\FormatEvents;
use Migration\Components\Faker\Formatter\GenerateEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


class Pick implements CompositeInterface
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
      *  @var double the figure to use as probability cutoff 
      */
    protected $probability;
    
    /**
      *  Class construtor
      *
      *  @access public
      *  @return void
      *  @param string $id the schema name
      *  @param CompositeInterface $parent
      *  @param EventDispatcherInterface $event
      *  @param double $probability
      */
    public function __construct($id, CompositeInterface $parent, EventDispatcherInterface $event,$probability)
    {
        $this->id = $id;
        $this->setParent($parent);
        $this->event = $event;
        
        if (is_numeric($probability) === FALSE) {
            throw new FakerException('Probability must be a number');
        }

        $probability = (double) $probability;

        if ($probability > 100 || $probability < 0) {
            throw new FakerException('Probability must be a between 0-1 or 0-100');
        }

        
        $this->probability = $probability;
    }
    
    
    //  -------------------------------------------------------------------------


     /**
      * Fenerates a random binominal distributed integer. 
      *
      *  @source http://www.php.net/manual/en/function.rand.php#107712 
      */
    function binRand($min = null, $max = null)
    {
        $min = ($min) ? (int) $min : 0;
        $max = ($max) ? (int) $max : PHP_INT_MAX;
        
        $range = range($min, $max);
        $average = array_sum($range) / count($range);
        
        $dist = array();
        for ($x = $min; $x <= $max; $x++) {
            $dist[$x] = -abs($average - $x) + $average + 1;
        }
        
        $map = array();
        foreach ($dist as $int => $quantity) {
            for ($x = 0; $x < $quantity; $x++) {
                $map[] = $int;
            }
        }
        
        shuffle($map);
        return current($map);
    }
       
   //  -------------------------------------------------------------------------
    
    /**
      *  @inheritdoc 
      */
    public function generate($rows,$values = array())
    {
        
        $index = ($this->binRand(1,100) <= $this->probability) ? 0 : 1;
        
        if(isset($this->child_types[$index]) == false) {
           throw new FakerException('Pick must have TWO types set');
        }

        return $this->child_types[$index]->generate($rows,$values);
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
        
        if(count($this->getChildren()) <= 1) {
          throw new FakerException('Pick must have at least 2 types');
        }

        return true;  
        
    }

    //  -------------------------------------------------------------------------
}
/* End of File */