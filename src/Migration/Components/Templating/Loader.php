<?php
namespace Migration\Components\Templating;

use Migration\Io\IoInterface;
use Migration\Components\Templating\Exception as TemplatingException;
use \Twig_Environment;
/*
 * class Loader
 */

class Loader
{
    /**
      *  @var Twig_Environmenmt
      */
    protected $twig_environment;


    public function load($name, array $vars = array())
    {
        # on first call setup our twig environment

        if($this->twig_environment == null){
            $loader = new TwigLoader($this->getIo());
            $this->twig_environment = new Twig_Environment($loader, array(
                'debug' => false,
                'autoescape' => false
                ));
        }

        if(is_array($vars) === false) {
            throw new TemplatingException('Vars param must be an array');
        }
        
        # load the template
        $template =  $this->twig_environment->loadTemplate($name);
        
        return new Template($template,$vars);
    }


    /*
     * __construct()
     *
     * @param Migration\Io\IoInterface the input output class
     * @return void
     * @access public
     */
    public function __construct(IoInterface $io)
    {
        $this->io = $io;
    }


    //--------------------------------------------------------------------
    /**
     * Input Output controller
     *
     *  @var Migration\Io\IoInterface
    */
    protected $io;

   /**
    * Fetches the Io Class
    *
    * @return Migration\Io\IoInterface
    */
    public function getIo()
    {
        return $this->io;
    }


    //---------------------------------------------------------------------
}
/* End of File */
