<?php
namespace Migration;

use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Finder\Finder;
use \Symfony\Pimple\Pimple;

class Project extends Pimple
{

    /**
      *  function getPath
      *
      *  @return \Migration\Path
      *  @access public
      */
    public function getPath()
    {
         return $this['project_path'];
    }

    
    /**
      *  Function getDataPath
      *
      *  @return \Migration\Path
      *  @access public
      */
    public function getDataPath()
    {
          return $this['data_path'];
     
    }
    
    

    //  -------------------------------------------------------------------------
    # Constructor

    /**
     * Function __construct
     *
     * Class Constructor
     *
     *  @return void
     *  @param \Migration\path $path
     */
    public function __construct(\Migration\Path $path)
    {
        $this['project_path'] = $path;
        $this['default'] = 'default';
        $this['schema_name'] = 'default';
    }


    //  -------------------------------------------------------------------------
    # Creats a new project


    public function build(\Migration\Io\Io $folder, \Migration\Io\Io $skelton, \Symfony\Component\Console\Output\OutputInterface $output)
    {

        $mode = 0777;

        #check if root folder exists
        if(is_dir($folder->getBase()) === TRUE) {
            # check if folder is empty
            $files = $folder->iterator();

            foreach($files as $file) {
                throw new \RuntimeException('Root Directory must be EMPTY');
            }

        } else {

            throw new \RuntimeException('Root directory does not exist');
        }

        #make config folders
        $config_path = $folder->getBase() . DIRECTORY_SEPARATOR .'config';

        if (mkdir($config_path,$mode) === TRUE) {
                $output->writeln('<info>Created Config Folder</info>');

                //copy files into it
                $files = $skelton->iterator('config');

                foreach($files as $file){
                    if($this->copy($file,$config_path) === TRUE) {
                       $output->writeln('++ Copied '.basename($file));
                    }

                }

            }

            #make migration Folder
            $migration_path = $folder->getBase() . DIRECTORY_SEPARATOR . 'migration';
            if (mkdir($migration_path,$mode) === TRUE) {
                $output->writeln('<info>Created Migration Folder</info>');

                //copy files into it
                $files = $skelton->iterator('migration');


                foreach($files as $file){
                    if($this->copy($file,$migration_path) === TRUE) {
                       $output->writeln('++ Copied '.basename($file));
                    }

                }
            }

            #make template folder
            $template_path = $folder->getBase() . DIRECTORY_SEPARATOR . 'template';
            if (mkdir($template_path,$mode) === TRUE) {
                $output->writeln('<info>Created Template Folder</info>');

                 //copy files into it
                $files = $skelton->iterator('template');


                foreach($files as $file){
                    if($this->copy($file,$template_path) === TRUE) {
                       $output->writeln('++ Copied '.basename($file));
                    }

                }


            }
            
             #make faker extension folder
            $extension_path = $folder->getBase() . DIRECTORY_SEPARATOR . 'extension';
            if (mkdir($extension_path,$mode) === TRUE) {
                $output->writeln('<info>Created Extension Folder</info>');

                 //copy files into it
                $files = $skelton->iterator('extension');

                foreach($files as $file){
                    if($this->copy($file,$extension_path) === TRUE) {
                          $output->writeln('++ Copied '.basename($file));
                    }
                }


            }

    }

    //-----------------------------------------------------------------------------

    /**
     * Copy a path to destination, check if file,directory or link
     * @param string $source      The Source File
     * @param string $destination The Destination File
     * @return boolean
     */
    public function copy(\Symfony\Component\Finder\SplFileInfo $source,$destination)
    {
        $new_path = $destination . DIRECTORY_SEPARATOR. $source->getRelativePathname();

        #Test if Source is a link
        if($source->isLink()) {
           return symlink($source,$new_path);
        }

        # Test if source is a directory
        if($source->isDir()){
            return mkdir($new_path);
        }

        #Test if Source is a file
        if($source->isFile()) {
            return copy($source,$new_path);

        }

        return FALSE;

    }

    //  -----------------------------------------------------------------------------
    # Database

    /**
      *  function getDatabase
      *
      *  @access public
      *  @return  the configured database handler
      */
    public function getDatabase()
    {
        return $this['database'];
    }
   
   

    //  -------------------------------------------------------------------------
    # Manager loaders


    /**
      *  function getConfigManager
      *
      *  @access public
      *  @return \Migration\Components\Config\Manager an instance of the config component manager
      */
    public function getConfigManager()
    {
        return $this['config_manager'];
    }

   
    /**
      *  function getTemplateManager
      *
      *  @access public
      *  @return \Migration\Components\Templating\Manager an instance of the templating component manager
      */
    public function getTemplatingManager()
    {
        return $this['template_manager'];
    }

    /**
      *  function getMigrationManager
      *
      *  @access public
      *  @return \Migration\Components\Migration\Manager an instance of the migration component manager
      */
    public function getMigrationManager()
    {
        return $this['migration_manager'];
    }

     /**
      *  function getMigrationManager
      *
      *  @access public
      *  @return \Migration\Components\Migration\Manager an instance of the migration component manager
      */
    public function getWritterManager()
    {
        return $this['writter_manager'];
    }    
    

    //  -------------------------------------------------------------------------
    # Debug Log

    /**
      *  function getLogger
      *
      *  @access public
      *  @return \Monolog\Logger an instance of the debug logger
      */
    public function getLogger()
    {
        return $this['logger'];
    }

    //  -------------------------------------------------------------------------
    # Config Name

    /**
      * function setConfigName
      *
      * @access public
      * @param string $name the name of config file to use
      */
    public function setConfigName($name = 'default')
    {
        $this['config_name'] = $name;
    }

    /**
      * function getConfigName
      *
      * @access public
      * @return string the name of the config file to use
      */
    public function getConfigName()
    {
        return $this['config_name'];
    }


    //  -------------------------------------------------------------------------
    # Schema Name

    /**
      * function setSchemaName
      *
      * @access public
      * @param string $name;
      */
    public function setSchemaName($name)
    {
        $this['schema_name'] = $name;
    }

    /**
      *  function getSchemaName
      *
      *  @access public
      *  @return string schema name;
      */
    public function getSchemaName()
    {
        return $this['schema_name'];
    }

    //  -------------------------------------------------------------------------
    # Symfony Console

    /**
      *  function getConsole
      *
      *  @access public
      *  @return \Migration\Command\Base\Application
      */
    public function getConsole()
    {
        return $this['console'];
    }

    //  -------------------------------------------------------------------------
    # Detect project folder

    /**
      *  static function detect
      *
      *  Will check if a project directory given in path
      *  matches the folder standard folder layout
      *
      *  @param string $path the path to check
      *  @return boolean true if folder internals match expected layout
      */
    public static function detect($path)
    {
        $path = rtrim($path,'/');

        #check for config folder
        if(is_dir($path . DIRECTORY_SEPARATOR . 'config') === false) {
            return false;
        }

        #check for migration folder
        if(is_dir($path . DIRECTORY_SEPARATOR . 'migration') === false) {
            return false;
        }

        #check for template folder
        if(is_dir($path . DIRECTORY_SEPARATOR . 'template') === false) {
            return false;
        }

        return true;
    }
}
/* End of File */
