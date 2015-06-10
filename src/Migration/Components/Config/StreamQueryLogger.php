<?php
namespace Migration\Components\Config;

use Doctrine\DBAL\Logging\SQLLogger;
use  Psr\Log\LoggerInterface;
use \Closure;

/**
  *  Stream logger will write queries to a stream ie monolog
  *  
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class StreamQueryLogger implements SQLLogger
{
    /**
      *  @var array[] continer for query history 
      */
    public $queries = array();
    
    /**
      *  @var integer the unix time query started 
      */
    protected $start;
    
    /**
      *  @var current index of the last query 
      */
    public $currentQuery = 0;
    
    /**
      *  @var  Psr\Log\LoggerInterface the logger instance
      */
    protected $monolog;
    
    /**
      *  @var array[] the last query 
      */
    protected $last_query;
    
    
    public function __construct(LoggerInterface $monolog)
    {
        $this->monolog = $monolog;
    }
    
  
    /**
     * Mark when query is run
     *
     * @return void
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $this->start = microtime(true);
        $this->queries[$this->currentQuery] = array('sql' => $sql, 'params' => $params, 'types' => $types, 'executionMS' => 0);
    }

    /**
     * Mark the last started query as stopped. This can be used for timing of queries.
     *
     * @return void
     */
    public function stopQuery()
    {
        # set the elapsed time
        $this->queries[$this->currentQuery]['executionMS'] = microtime(true) - $this->start;
        
        # call the output format closure
        $this->monolog->addInfo($this->queries[$this->currentQuery]['sql'],array(
                                       'execution' => $this->queries[$this->currentQuery]['executionMS'],
                                       'params'    => $this->queries[$this->currentQuery]['params']
                                ));
        
        # save last query for one more iteration
        $this->last_query = $this->queries[$this->currentQuery];
        
        # remove query for buffer
        unset($this->queries[$this->currentQuery++]);
    }
    
    //------------------------------------------------------------------
    # Accessors
    
    public function lastQuery()
    {
        return $this->last_query;
    }
    
    /**
     * Return the assigned logger
     * 
     * @return  Psr\Log\LoggerInterface
     */ 
    public function getLogger()
    {
        return $this->monolog;
    }
    
    //------------------------------------------------------------------

    /**
      *   Class Destructor
      *   
      */
    public function __destruct()
    {
        unset($this->last_query);
        unset($this->monolog);
        unset($this->queries);
    }
    
    
}
/* End of File */