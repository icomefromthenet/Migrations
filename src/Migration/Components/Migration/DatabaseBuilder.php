<?php

namespace Migration\Components\Migration;

use Monolog\Logger as Logger;
use Symfony\Component\Console\Output\OutputInterface as Output;
use Doctrine\DBAL\Connection;
use Migration\Components\Migration\MigrationFileInterface;
use Migration\Components\Migration\Collection;


class DatabaseBuilder
{
    protected $database;

    protected $output;

    protected $logger;


    public function __construct(Logger $log, Ouput $output, Connection $db)
    {
        $this->database = $db;
        $this->output = $output;
        $this->logger = $log;

    }

    //  -------------------------------------------------------------------------
    # Enable / Disable FK

    public function disableFK()
    {

    }

    public function enableFK()
    {

    }

    //  -------------------------------------------------------------------------
    # Show

    /**
      *  Return a list of database tables found in
      *  the database
      *
      *  @access public
      *  @return array(tables);
      */
    public function show()
    {

    }

    //  -------------------------------------------------------------------------
    # Drop

    /**
      *  Will Drop the table from the database
      *
      *  @param string $table
      *  @access public
      */
    public function drop($table)
    {

    }

    //  -------------------------------------------------------------------------
    # Apply

    /**
      *  Apply The Migration Table Schema
      *
      *  @access public
      *  @return boolean
      */
    public function apply()
    {


    }

    //  -------------------------------------------------------------------------
    # Build

    public function build(MigrationFileInterface $schema, MigrationFileInterface $test_data, Collection $collection)
    {
        # Start transaction

        try {

            # Disable FK
            $this->disableFK();

            # Get the tables tables
            $tables = $this->show();

            # Drop Each Table
            foreach($tables as $table) {

                $this->drop($table);
            }


            # Enable FK
            $this->enableFK();

            # Apply inital schema
            $new_schema = $schema->getClass();
            $new_schema->up($this->database);

            # Apply Migration Table Schema
            $this->apply();

            # Apply Migrations
            $collection->latest();

            # Apply Test Data
            $new_test_data = $test_data->getClass();
            $new_test_data->up($this->database);

        }
        catch(\Exception $e) {

            # revert the transaction

            throw $e;
        }

        # Apply the transaction


    }

}
/* End of File */
