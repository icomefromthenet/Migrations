<?php
namespace Migration\Io;

use Symfony\Component\Finder\Finder as Finder;


/*
 * class Io
 */

class Io implements IoInterface
{
    /*
     * __construct()
     *
     * @param string $base_folder the path to a project
     * @return void
     */

    public function __construct($base_folder)
    {
        $this->setBase($base_folder);
    }

    //----------------------------------------------------------------------

    /**
     * Builds a Path inside the project
     *
     * @param array $folders array of subfolders to join together
     * @return string built path
     */

    public function path($folders = null)
    {
        $folders_str = '';
        $path = '';

        if(is_array($folders) === TRUE) {
            $folders_str = join(DIRECTORY_SEPARATOR,$folders);
        } else {
            # Remove the First and Last DIR Char.
            $folders_str = ltrim(rtrim($folders,'/'));
        }

        if(empty($this->dir) === true) {
            $path = rtrim($this->getBase(),'/') . DIRECTORY_SEPARATOR;
        } else  {
            $path = rtrim($this->getBase(),'/') . DIRECTORY_SEPARATOR . $this->dir . DIRECTORY_SEPARATOR;
        }

        if((string)$folders_str !== '')
        {
            $path = $path . $folders_str . DIRECTORY_SEPARATOR;
        }

        return $path;
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  Check if dir is empty of files
      *
      *  @param string $path to add to default
      *  @return boolean true if dir is empty false otherwise
      */
    public function isEmpty($path ='')
    {
        $path = $this->path($path);
        $empty = true;
        
        # exist and has permissions.        
        if(is_dir($path) === false) {
            throw new DirectoryNotExistException("Dir at $path not exist or not have correct permissions");            
        }
        
        $files = scandir($path);
        
        # files are found set to false.
        if (((count($files) > 2))) {
            $empty = false;
        }
        
        return $empty;
    }

    //----------------------------------------------------------------------

    /**
     * Loads a file from the path
     *
     *  @param string $name filename
     *  @param mixed folders array of folders to append
     *  @param mixed
     */

    public function contents($name, $folders = null)
    {
        $path = $this->path($folders);

        if(is_file($path . $name) === FALSE){
            throw new FileNotExistException('Can not find file named: '.$path . $name);
        }

        return file_get_contents($path . $name);

    }

    //  -------------------------------------------------------------------------


    /**
     * Loads a file from the path
     *
     *  @param string $name filename
     *  @param mixed folders array of folders to append
     *  @param mixed
     *  @param $object true to return SplFileInfo
     */
    public function load($name,$folders,$object = false)
    {
        $path = $this->path($folders);

        if(is_file($path . $name) === FALSE){
            throw new FileNotExistException('Can not find file named: '.$path . $name);
        }

        if($object === true) {
            return new \SplFileInfo($path.$name);
        } else {
            return require($path . $name);
        }
    }


    //----------------------------------------------------------------------

    /**
      * Check if a config is found using the supplied name
      *
      * @param string $name the config name
      * @param mixed $folders extra folder to append to path
      * @return boolean true if found false otherwise
      */

    public function exists($name,$folders = null)
    {
        return is_file($this->path($folders) . $name);
    }

    //----------------------------------------------------------------------


    /**
      * Writes a file to the given path
      *
      *  @param string $filename the filename
      *  @param array $folders the file path relative to project/self::DIR/
      *  @param string $content the file content
      *  @param boolean $overrite defaults to false
      *  @return boolean true if file written
      */
    public function write($filename,$folders,$content,$overrite = FALSE)
    {



        # Remove the first and last dir char.
        $filename = ltrim(rtrim($filename,'/'),'/');


        $path  = $this->path($folders) . $filename;
        

        #check if path exists and overrite dir
        if(is_file($path) === TRUE && $overrite === FALSE) {
            throw new FileExistException("File $path exists already");
        }


        #check if path writable
        if(is_writable(dirname($path)) === FALSE) {
            throw new PermissionException("Path ". dirname($path) ." is not writable");
        }

        return file_put_contents($path,$content);

    }

    //----------------------------------------------------------------------

    /**
     * Build a file/directory Iterator
     *
     * @param string $subfolder The subfolder to pass to the finder.
     * @return Symfony\Component\Finder\Finder;
     */

    public function iterator($path = NULL)
    {
         if($path !== NULL){
            $iterator = Finder::create()->ignoreDotFiles(true)->in($this->path($path))->getIterator();
         } else{
            $iterator = Finder::create()->ignoreDotFiles(true)->in($this->getBase())->getIterator();
         }

        return $iterator;

    }

    //----------------------------------------------------------------------

    /**
     * The path to the project base folder
     *
     * @var string
     */
    protected $project_path ='';

    /**
     * Returns the base project path
     *
     * @return string project path
     */
    public function getBase()
    {
        return $this->project_path;
    }

    /**
     * Sets the project path
     *
     * @param string $path the project path
     */
    public function setBase($path)
    {
        $this->project_path = rtrim($path,'/');
    }

    //---------------------------------------------------------------------


    /**
      *  function mkdir
      *
      *  create a directory relative to the base dir
      *
      *  @access public
      *  @param string $name the folder name
      *  @param array $folders path
      *  @return boolean
      *  @throws DirectoryExistsException
      */
    public function mkdir($name,$folders = null)
    {
        $path = $this->path($name,$folders);

        if(is_dir($path) === true) {
            throw new DirectoryExistsException("Unable to create directory at $path");
        }

        return \mkdir($path);

    }

    //  -------------------------------------------------------------------------
	# Project Folder (folder to use inside the project)

     /**
      *  @var string $DIR the name of the folder under the project
      */
    protected $dir = '';


    /**
      *  Sets the project folder
      *
      *  @access public
      *  @return void
      *  @param string $folder the project folder inside the project
      */
    public function setProjectFolder($folder)
    {
        $this->dir = $folder;
    }

    /**
      *  Gets the project folder
      *
      *  @access public
      *  @return string
      */
    public function getProjectFolder()
    {
        return $this->dir;
    }

    //  -------------------------------------------------------------------------
}
/* End of File */
