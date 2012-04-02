<?php
namespace Migration\Components\Templating;

use Migration\Components\Templating\Excepion as TemplatingException;
use \Twig_Template;

class Template
{

    protected $template;


    protected $vars;
    

    public function __construct(Twig_Template $template,array $vars)
    {
        $this->vars = $vars;
        $this->template = $template;
    }
    
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


    public function getData()
    {
        return $this->vars;
    }
    
    public function setData(array $data)
    {
        $this->vars = $data;
    }
    
    
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
    
}


/* End of File */