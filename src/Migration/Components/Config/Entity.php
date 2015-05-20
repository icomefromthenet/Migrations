<?php
namespace Migration\Components\Config;

use Migration\Components\Config\InvalidConfigException,
    Migration\Components\Config\EntityInterface;


class Entity implements EntityInterface
{

    //  ----------------------------------------------------------------
    # Properties

    protected $dbschema;

    public function getSchema()
    {
        return $this->dbschema;
    }

    public function setSchema($schema)
    {
        $this->dbschema = $schema;
    }
    
    
    //------------------------------------------------------------------------

    protected $dbuser;

    public function getUser()
    {
        return $this->dbuser;
    }
    
    public function setUser($user)
    {
        $this->dbuser = $user;
    }

    //------------------------------------------------------------------------

    protected $dbtype;

    public function getType()
    {
        return $this->dbtype;
    }
    
     public function setType($type)
     {
        $this->dbtype = $type;
     }

    //------------------------------------------------------------------------

    protected $dbport;

    public function getPort()
    {
        return $this->dbport;
    }
    
    public function setPort($port)
    {
        $this->dbport = $port;
    }

    //------------------------------------------------------------------------

    protected $dbhost;

    public function getHost()
    {
        return $this->dbhost;
    }

    public function setHost($host)
    {
        $this->dbhost = $host;
    }

    //------------------------------------------------------------------------

    protected $dbpassword;

    public function getPassword()
    {
        return $this->dbpassword;
    }
    
    public function setPassword($password)
    {
        $this->dbpassword = $password;
    }


    //------------------------------------------------------------------------

    protected $migrationtable;

    public function getMigrationTable()
    {
        return $this->migrationtable;
    }
    
    public function setMigrationTable($table)
    {
        $this->migrationtable = $table;
    }

    //---------------------------------------------------------------------

    protected $dbmemory;
    
    public function getMemory()
    {
        return $this->dbmemory;
    }
    
    public function setMemory($memory)
    {
        $this->dbmemory = $memory;
    }
    
    //------------------------------------------------------------------

    protected $dbsocket;

    public function getUnixSocket()
    {
        return $this->dbsocket;
    }
    
    public function setUnixSocket($socket)
    {
        $this->dbsocket = $socket;
    }
    
    //------------------------------------------------------------------
    
    protected $dbpath;
    
    public function getPath()
    {
        return $this->dbpath;
    }
    
    public function setPath($path)
    {
        $this->dbpath = $path;
    }
        
    //------------------------------------------------------------------
    
    protected $dbcharset;
    
    public function getCharset()
    {
        return $this->dbcharset;
    }
    
    public function setCharset($set)
    {
        $this->dbcharset = $set;
    }
    
    //------------------------------------------------------------------
    
    
    protected $connName;
    
    public function setConnectionName($name)
    {
        if(true === empty($name)) {
            throw new InvalidConfigException('Connection Name must not be empty');
        }
     
        $name = trim(strtoupper($name));
        
        $aSplit = preg_split('/$[a-zA-Z0-9.@_]+/',$name);
     
        
     
        if(count($aSplit) > 1) {
            throw new InvalidConfigException('There are invalid characters in the connection name at::'.$name);
        }
     
        $this->connName = $name;
    }
    
    public function getConnectionName()
    {
        return $this->connName;
    }
    
   //------------------------------------------------------------------
  
   protected $platformOptions = array(); 
   
   
   public function addPlatformOption($name,$value)
   {
       $this->platformOptions[$name] = $value;
   }
   
   public function getPlatformOptions()
   {
       return $this->platformOptions;
   }
    
}
/* End of File */
