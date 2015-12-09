<?php
namespace Migration\Components\Migration;

use \SplFileInfo;
use Migration\Components\Migration\Exception\EntityNotExistException;
use Migration\Autoload;

class MigrationFile implements MigrationFileInterface
{
    /**
      *  @var \SplFileInfo
      */
    protected $file;

    /**
     * @var Migration\Autoload
     */ 
    protected $oAutoloader;

    /**
      *  __construct()
      *
      *  @access public
      *  @param \SplFileInfo
      *  @param integer $stamp the unix-timestamp from the filename
      *  @param boolean $applied if the migration has been run against the db
      *  @return void
      */
    public function __construct(Autoload $oAutoloader ,\SplFileInfo $file,$stamp,$applied = false)
    {
        $this->file         = $file;
        $this->stamp        = (integer) $stamp;
        $this->applied      = (boolean) $applied;
        $this->oAutoloader   = $oAutoloader;
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
        $basename = rtrim(basename($this->getFilename()),'.php');
        
        $sClass  = $this->oAutoloader->getMigrationNamespace();
        $sClass .= '\\'.$basename;
        
        if($this->oAutoloader->findFile($sClass) === null) {
            throw new EntityNotExistException('Entity does not exist at '.$sClass);
        }
        
        # check if class has been required aleady, this can happen if in shellmode or in unit tests
        if(!class_exists($sClass)) {
            $this->oAutoloader->loadClass($sClass);    
        }
        
        return new $sClass();
    }
    

    //  -------------------------------------------------------------------------
}
/* End of File */
