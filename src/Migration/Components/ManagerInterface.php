<?php

namespace Migration\Components;

use Monolog\Logger as Logger;
use Symfony\Component\Console\Output\OutputInterface as Output;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Migration\Io\IoInterface;
use Doctrine\DBAL\Connection;

/*
 * interface ManagerInterface
 */

interface ManagerInterface
{

   

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
