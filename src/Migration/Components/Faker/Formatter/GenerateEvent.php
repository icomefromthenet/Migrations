<?php
namespace Migration\Components\Faker\Formatter;

use Symfony\Component\EventDispatcher\Event;
use Migration\Components\Faker\Composite\CompositeInterface;

/**
  *  Event is used for events found in \Migration\Components\Faker\FormatEvents; 
  */
class GenerateEvent extends Event
{
    /**
      *  @var mixed associate array of generated values
      */
    protected $values;
    
    /**
      *  @var string the id of the type used to generate 
      */
    protected $id;

    /**
      *  @var  Migration\Components\Faker\Composite\CompositeInterface
      */
    protected $type;
    
    /**
      *  Class constructor
      *
      *  @param CompositeInterface $formatter
      *  @param mixed[] $values associate array of values generated
      *  @param string $id the component id example to schema name
      */
    public function __construct(CompositeInterface $type  ,array $values,$id)
    {
        $this->values = $values;
        $this->id = $id;
        $this->type = $type;
    }
    
    /**
      *  Fetch the generated values
      *
      *  @return mixed[]
      */
    public function getValues()
    {
        return $this->values;
    }
    
    /**
      *  Fetch the id of the generating type
      *
      *  @return string the type name
      */
    public function getId()
    {
        return $this->id;
    }
    
    /**
      *  Fetch the bound generating type
      *
      *  @return CompositeInterface
      */
    public function getType()
    {
        return $this->type;
    }
}


/* End of File */