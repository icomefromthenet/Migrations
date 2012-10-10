<?php
namespace Migration\Components\Templating;

use Migration\Components\Templating\Excepion as TemplatingException;
use \Twig_Template;

class Template
{
    
    //  -------------------------------------------------------------------------

    /**
      *  @var Twig_Template $template 
      */
    protected $template;
    
    /**
      *  @var arrays $vars
      */
    protected $vars;
    
    //  -------------------------------------------------------------------------
    
    /**
      *  Public Constructor
      *
      *  @param Twig_Template $template
      *  @param array $vars
      */
    public function __construct(Twig_Template $template,array $vars)
    {
        $this->vars = $vars;
        $this->template = $template;
    }

    //  -------------------------------------------------------------------------
    
    /**
      *  Render a template
      *
      *  @return string
      *  @param array $vars will merge into default vars passed in constructor
      */    
    public function render(array $vars = array())
    {
        try {
            $vars = array_merge($this->vars,$vars);
            return $this->template->render($vars);
        }
        catch(\Twig_Exception $e ) {
            throw new TemplatingException($e->getMessage());
        }
    }
    
    
    //  -------------------------------------------------------------------------
    # Properties
    
    /**
      *  Get Data
      *
      *  @return array
      */
    public function getData()
    {
        return $this->vars;
    }
    
    /**
      *  Set Data
      *
      *  @param array $data
      */
    public function setData(array $data)
    {
        $this->vars = $data;
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  Returns the twig template
      *
      *  @return Twig_Template
      *  @access public
      */
    public function getTemplate()
    {
        return $this->template;
    }
    
    //  -------------------------------------------------------------------------
}
/* End of File */