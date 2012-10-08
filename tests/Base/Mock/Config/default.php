<?php

/* Database Config file */

return array (
  'type' => 'pdo_mysql',
  'schema' => 'sakila',
  'user' => 'root',
  'password' => 'vagrant',
  'host' => 'localhost',
  'port' => 3306,
  'migration_table' => 'migrations_data',
  'socket' => false,
  'path' => NULL,
  'memory' => NULL,
  'charset' => false,
);


/* End of Config File */
