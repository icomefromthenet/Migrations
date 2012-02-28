<?php

namespace Migration\Components;

use Monolog\Logger as Logger;
use Symfony\Component\Console\Output\OutputInterface as Output;
use Migration\Io\IoInterface;
use Doctrine\DBAL\Connection;

/*
 * interface ManagerInterface
 */

interface ManagerInterface
{

    /**
      *  Class constructor
      *
      * @param IoInterface $io the file writter
      * @param Logger $log the log class
      * @param Output $output console output class
      * @param Connection $database the optional database connection
      *
      */
    public function __construct(IoInterface $io,Logger $log, Output $output, Connection $database = null);


    /**
      *  function getLoader
      *
      *  @return LoaderInterface
      *  @access public
      */
    public function getLoader();



    /**
      *  function getLoader
      *
      *  @return WriterInterface
      *  @access public
      */
    public function getWriter();


}
/* End of File */
