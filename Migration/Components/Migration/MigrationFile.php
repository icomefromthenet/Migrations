<?php
namespace Migration\Components\Migration;

use \SplFileInfo;
use Migration\Components\Migration\Exception\EntityNotExistException;

class MigrationFile implements MigrationFileInterface
{
    /**
      *  @var \SplFileInfo
      */
    protected $file;


    /**
      *  __construct()
      *
      *  @access public
      *  @param \SplFileInfo
      *  @param integer $stamp the unix-timestamp from the filename
      *  @param boolean $applied if the migration has been run against the db
      *  @return void
      */
    public function __construct(\SplFileInfo $file,$stamp,$applied = false)
    {
        $this->file = $file;
        $this->stamp = (integer) $stamp;
        $this->applied = (boolean) $applied;
    }

    //  -------------------------------------------------------------------------
    # Get Timestamp

    protected $stamp;

    public function getTimestamp()
    {
        return $this->stamp;
    }

    //  -------------------------------------------------------------------------
    # From SplFileInfo

    public function getRealPath()
    {
        return $this->file->getRealPath();
    }

    public function getBasename ($suffix_omit)
    {
        return $this->file->getBasename($suffix_omit);
    }

    public function getExtension()
    {
        return $this->file->getExtension();
    }

    public function getFilename()
    {
        return $this->file->getFilename();
    }

    public function getPath()
    {
        return $this->file->getPath();
    }

    public function getPathname()
    {
        return $this->file->getPathname();
    }

    public function openFile ($open_mode = 'r', $use_include_path = false , $context = NULL)
    {
        return $this->file->openFile($open_mode,$use_include_path,$context);
    }

    public function __toString()
    {
        return (string) $this->file;
    }

    //  -------------------------------------------------------------------------
    # Applied (has been run against the database)

    protected $applied = false;

    public function getApplied()
    {
        return $this->applied;
    }

    public function setApplied($applied)
    {
        $this->applied = (boolean) $applied;
    }

    //  -------------------------------------------------------------------------
    # Get Class (get the EntityInterface)

    /**
      *  Require the class and return an instance
      *
      *  @access public
      *  @return EntityInterface
      */
    public function getEntity()
    {
       $class_name = basename($this->getFilename());

        # require the class
        require($this->getRealPath());

        # test if it exists
        if(class_exists($class_name) === false) {
            throw new EntityNotExistException(sprintf('migration class %s does not exist',$class_name));
        }

        return new $class_name;
    }

    //  -------------------------------------------------------------------------
}
/* End of File */
