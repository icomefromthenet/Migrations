<?php
namespace Migration;

use Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Finder\Finder,
    Symfony\Pimple\Pimple,
    Migration\Exception as MigrationException;

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
          if(is_dir($folder->getBase()) === false) {
               throw new MigrationException('Root directory does not exist');
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
            
          #make extension extension folder
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
      * function getConfigName
      *
      * @access public
      * @return string the name of the config file to use
      */
    public function getConfigName()
    {
        return  \Migration\Components\Config\Loader::DEFAULTNAME . \Migration\Components\Config\Loader::EXTENSION;
    }


    //  -------------------------------------------------------------------------
    # Config File
     
    /**
      *  Function getConfigFile
      *
      *  @access public
      *  @return \Migration\Components\Config\Entity
      */ 
    public function getConfigFile()
    {
          return $this['config_file'];
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

         #check for extension folder
        if(is_dir($path . DIRECTORY_SEPARATOR . 'extension') === false) {
            return false;
        }

        
        return true;
    }
    
    //------------------------------------------------------------------------------------------
    # From Ez-Components Database Factory : http://svn.ez.no/svn/ezcomponents/trunk/Database/src/factory.php
    
    
    /**
     * Returns the Data Source Name as a structure containing the various parts of the DSN.
     *
     * Additional keys can be added by appending a URI query string to the
     * end of the DSN.
     *
     * The format of the supplied DSN is in its fullest form:
     * <code>
     *  phptype(dbsyntax)://username:password@protocol+hostspec/database?option=8&another=true
     * </code>
     *
     * Most variations are allowed:
     * <code>
     *  phptype://username:password@protocol+hostspec:110//usr/db_file.db?mode=0644
     *  phptype://username:password@hostspec/database_name
     *  phptype://username:password@hostspec
     *  phptype://username@hostspec
     *  phptype://hostspec/database
     *  phptype://hostspec
     *  phptype(dbsyntax)
     *  phptype
     * </code>
     *
     * This function is 'borrowed' from PEAR /DB.php .
     *
     * @param string $dsn Data Source Name to be parsed
     *
     * @return array an associative array with the following keys:
     *  + phptype:  Database backend used in PHP (mysql, odbc etc.)
     *  + dbsyntax: Database used with regards to SQL syntax etc.
     *  + protocol: Communication protocol to use (tcp, unix etc.)
     *  + hostspec: Host specification (hostname[:port])
     *  + database: Database to use on the DBMS server
     *  + username: User name for login
     *  + password: Password for login
     */
    public function parseDSN( $dsn )
    {
        $parsed = array(
            'phptype'  => false,
            'dbsyntax' => false,
            'username' => false,
            'password' => false,
            'protocol' => false,
            'hostspec' => false,
            'port'     => false,
            'socket'   => false,
            'database' => false,
        );

        if ( is_array( $dsn ) )
        {
            $dsn = array_merge( $parsed, $dsn );
            if ( !$dsn['dbsyntax'] )
            {
                $dsn['dbsyntax'] = $dsn['phptype'];
            }
            return $dsn;
        }

        // Find phptype and dbsyntax
        if ( ( $pos = strpos( $dsn, '://' ) ) !== false )
        {
            $str = substr( $dsn, 0, $pos );
            $dsn = substr( $dsn, $pos + 3 );
        }
        else
        {
            $str = $dsn;
            $dsn = null;
        }

        // Get phptype and dbsyntax
        // $str => phptype(dbsyntax)
        if ( preg_match( '|^(.+?)\((.*?)\)$|', $str, $arr ) )
        {
            $parsed['phptype']  = $arr[1];
            $parsed['dbsyntax'] = !$arr[2] ? $arr[1] : $arr[2];
        }
        else
        {
            $parsed['phptype']  = $str;
            $parsed['dbsyntax'] = $str;
        }

        if ( !count( $dsn ) )
        {
            return $parsed;
        }

        // Get (if found): username and password
        // $dsn => username:password@protocol+hostspec/database
        if ( ( $at = strrpos( (string) $dsn, '@' ) ) !== false )
        {
            $str = substr( $dsn, 0, $at );
            $dsn = substr( $dsn, $at + 1 );
            if ( ( $pos = strpos( $str, ':' ) ) !== false )
            {
                $parsed['username'] = rawurldecode( substr( $str, 0, $pos ) );
                $parsed['password'] = rawurldecode( substr( $str, $pos + 1 ) );
            }
            else
            {
                $parsed['username'] = rawurldecode( $str );
            }
        }

        // Find protocol and hostspec

        if ( preg_match( '|^([^(]+)\((.*?)\)/?(.*?)$|', $dsn, $match ) )
        {
            // $dsn => proto(proto_opts)/database
            $proto       = $match[1];
            $proto_opts  = $match[2] ? $match[2] : false;
            $dsn         = $match[3];
        }
        else
        {
            // $dsn => protocol+hostspec/database (old format)
            if ( strpos( $dsn, '+' ) !== false )
            {
                list( $proto, $dsn ) = explode( '+', $dsn, 2 );
            }
            if ( strpos( $dsn, '/' ) !== false )
            {
                list( $proto_opts, $dsn ) = explode( '/', $dsn, 2 );
            }
            else
            {
                $proto_opts = $dsn;
                $dsn = null;
            }
        }

        // process the different protocol options
        $parsed['protocol'] = ( !empty( $proto ) ) ? $proto : 'tcp';
        $proto_opts = rawurldecode( $proto_opts );
        if ( $parsed['protocol'] == 'tcp' )
        {
            if ( strpos( $proto_opts, ':' ) !== false )
            {
                list( $parsed['hostspec'], $parsed['port'] ) = explode( ':', $proto_opts );
            }
            else
            {
                $parsed['hostspec'] = $proto_opts;
            }
        }
        elseif ( $parsed['protocol'] == 'unix' )
        {
            $parsed['socket'] = $proto_opts;
        }

        // Get dabase if any
        // $dsn => database
        if ( $dsn )
        {
            if ( ( $pos = strpos( $dsn, '?' ) ) === false )
            {
                // /database
                $parsed['database'] = rawurldecode( $dsn );
            }
            else
            {
                // /database?param1=value1&param2=value2
                $parsed['database'] = rawurldecode( substr( $dsn, 0, $pos ) );
                $dsn = substr( $dsn, $pos + 1 );
                if ( strpos( $dsn, '&') !== false )
                {
                    $opts = explode( '&', $dsn );
                }
                else
                { // database?param1=value1
                    $opts = array( $dsn );
                }
                foreach ( $opts as $opt )
                {
                    list( $key, $value ) = explode( '=', $opt );
                    if ( !isset( $parsed[$key] ) )
                    {
                        // don't allow params overwrite
                        $parsed[$key] = rawurldecode( $value );
                    }
                }
            }
        }
        return $parsed;
    }
    
}
/* End of File */
