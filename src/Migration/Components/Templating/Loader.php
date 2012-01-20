<?php
namespace Migration\Components\Templating;

use Migration\Io\IoInterface;

/*
 * class Loader
 */

class Loader
{
    /**
      *  @var Twig_Environmenmt
      */
    protected $twig_environment;



    public function load($name)
    {
        # on first call setup our twig environment

        if($this->twig_environment == null){
            $loader = new TwigLoader($this->getIo());
            $this->twig_environment = new \Twig_Environment($loader, array(
                'debug' => false
                ));
        }

        # load the template
        return $this->twig_environment->loadTemplate($name);

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
