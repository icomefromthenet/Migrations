<?php

/* Database Config file */

return array (
  0 => 
  array (
    'type' => 'pdo_sqlite',
    'schema' => NULL,
    'user' => false,
    'password' => false,
    'host' => NULL,
    'port' => NULL,
    'socket' => NULL,
    'path' => '',
    'memory' => true,
    'charset' => NULL,
    'connectionName' => 'DEMO.A',
    'migration_table' => 'migrations_data',
    'schemaFolder' => 'migration'
  ),
  1 => 
  array (
    'type' => 'pdo_sqlite',
    'schema' => NULL,
    'user' => false,
    'password' => false,
    'host' => NULL,
    'port' => NULL,
    'socket' => NULL,
    'path' => '',
    'memory' => true,
    'charset' => NULL,
    'connectionName' => 'DEMO.B',
    'migration_table' => 'migrations_data',
    'schemaFolder' => 'migration'
  ),
);


/* End of Config File */