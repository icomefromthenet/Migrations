<?php
namespace Migration\Components\Faker\Composite;

use Migration\Components\Faker\Exception as FakerException;

class Schema implements CompositeInterface
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
      *  Class construtor
      *
      *  @access public
      *  @return void
      *  @param string $id the schema name
      *  @param CompositeInterface $parent (Optional for this object)
      */
    public function __construct($id , CompositeInterface $parent = null)
    {
         $this->id = $id;
            
        if($parent !== null) {
            $this->setParent($parent);
        }
    }
    
    /**
      *  @inheritdoc 
      */
    public function generate($rows,$values = array())
    {
        foreach($this->child_types as $type) {
            $type->generate($rows,$values);
        }
    }
    
    
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
    
    
}

/* End of File */