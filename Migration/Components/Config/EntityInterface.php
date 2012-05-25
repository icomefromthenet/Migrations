<?php
namespace Migration\Components\Config;

/*
 * interface EntityInterface
 */

interface EntityInterface 
{
    /**
      *   Fetch the schema
      *
      *   @return string the scheam name
      */
    public function getSchema();
    
    public function setSchema($schema);
    
    /**
      *   Fetches the database user
      *
      *   @return string the user name
      */
    public function getUser();
    
    public function setUser($user);

    /**
      *   Fetch the doctine database type
      *
      *   @return string the doctrine db type name
      */
    public function getType();
    
    public function setType($type);

    /**
      *   Fetch The current port number
      *
      *   @return integer the port number
      */
    public function getPort();

    public function setPort($port);
    
    /**
      *   Fetch the Host name
      *
      *   @return string the host name
      */
    public function getHost();

    public function setHost($host);
    
    /**
      *   Fetch the password
      *
      *   @return string the password in plaintext
      */
    public function getPassword();

    public function setPassword($password);
    
    /**
      *  Fetch the uses memory (sqlite)
      *
      *  @return string the param
      */
    public function getMemory();
    
    public function setMemory($memory);
    
    /**
      *  Fetch a path to the unix socket
      *  to connect to the database
      *
      *  @return string path to the socket
      */
    public function getUnixSocket();
    
    public function setUnixSocket($socket);
    
    
    /**
      *  Fetch the path to the database file (sqlite)
      *
      *  @access public
      *  @return string the path to db file (sqlite)
      */
    public function getPath();
    
    public function setPath($path);
    
    
    /**
      *  The character set for supported connections
      *
      *  @return stirng the name of the characterset
      */
    public function getCharset();
    
    public function setCharset($set);
    
    /**
    * function getMigrationTable
    *
    *  @return string the migration table
    */
    public function getMigrationTable();
    
    public function setMigrationTable($table);
    
}
/* End of File */