<?php
namespace Migration\Components\Faker\Composite;

use Migration\Components\Faker\Exception as FakerException;
use Doctrine\DBAL\Types\Type as ColumnType;

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
      *  Class construtor
      *
      *  @access public
      *  @return void
      *  @param string $id the schema name
      *  @param Table $parent 
      */
    public function __construct($id, Table $parent, ColumnType $type)
    {
        $this->id = $id;
        $this->setParent($parent);
        $this->column_type = $type;
        
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
    
    //  ----------------------------------------------------------------------------
    
    /**
      *  Fetch the Doctrine Column Type
      *
      *  @return Doctrine\DBAL\Types\Type
      *  @access public
      */    
    public function getColumnType()
    {
        return $this->column_type;
    }
    
    //  ----------------------------------------------------------------------------
    
}

/* End of File */