<?php
namespace Migration\Components\Faker\Formatter;

use Symfony\Component\EventDispatcher\Event;

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
      *  Class constructor
      *
      *  @param mixed[] $values associate array of values generated
      *  @param string $id the component id example to schema name
      */
    public function __construct(array $values,$id)
    {
        $this->values = $values;
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
}


/* End of File */