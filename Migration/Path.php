<?php
namespace Migration;

use Migration\Exception as MigrationException,
    Symfony\Component\Filesystem\Filesystem;

class Path
{

    /**
      *  @var $path a parsed path of the project
      */
    protected $path;

    /**
      *  function get
      *
      *  fetches the projects path
      *
      *  @return string the project path
      *  @access public
      */
    public function get()
    {
        return $this->path;
    }
    
    /**
      *  Set the path function
      *
      *  @return void
      *  @access public
      *  @param string $path the path to project folder
      */
    public function set($path)
    {
        $this->path = $path;
        
    }
    

    //  -------------------------------------------------------------------------


    public function __construct($path = '')
    {
         if($path === '') {
            $this->path = getcwd();
         }
        else {
            $this->path = $path;
        }

    }

    //  -------------------------------------------------------------------------


    /**
      *  function parse
      *
      *  Will parse argument given by the user and
      *  attempt to match to a realpath
      *
      *  @parma $project_folder the path given by the user
      *  @return mixed a full path or false otherwise
      *  @access public
      */
    public function parse($project_folder)
    {
        $fs = new Filesystem();
        
        # Step 1. Check for empty path.
        if ($project_folder === '') {
            // must mean use current directory
            $project_folder = getcwd() . DIRECTORY_SEPARATOR;
        }

        # Step 2. check if path is absolute or relative

        if($fs->isAbsolutePath($project_folder) === false) {
            $project_folder =  realpath($project_folder);
        }
        elseif(is_dir($project_folder) === false) {
           #if where still false lets append cwd to what we have
           $project_folder = getcwd(). DIRECTORY_SEPARATOR . rtrim(ltrim($project_folder,DIRECTORY_SEPARATOR),DIRECTORY_SEPARATOR);
        }

        $this->path = $project_folder;


        # load the extension bootstrap file
        $this->loadExtensionBootstrap();
       
        
        return $this->path;
    }
    
    /**
      *  Require the extension bootstrap file
      *
      *  @access public
      *  @return void
      *  @throws Migration/Exception 
      */
    public function loadExtensionBootstrap()
    {
        $extension_bootstrap = $this->path . DIRECTORY_SEPARATOR . 'extension'. DIRECTORY_SEPARATOR .'bootstrap.php';
        
        if(is_file($extension_bootstrap) === false) {
            throw new MigrationException(__CLASS__.'::'.__METHOD__.':: extension bootstrap file not found at '.$extension_bootstrap);    
        }
        
        require $extension_bootstrap;
    }
    
}
/* End of File */
