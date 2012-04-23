<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Config
 * @subpackage Reader
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

namespace Zend\Config\Reader;

use Zend\Config\Reader,
    Zend\Config\Exception;

/**
 * Yaml config reader.
 *
 * @category   Zend
 * @package    Zend_Config
 * @subpackage Reader
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Yaml implements Reader
{
    /**
     * Directory of the JSON file
     * 
     * @var string
     */
    protected $directory;
    /**
     * Yaml decoder callback
     * 
     * @var callable
     */
    protected $yamlDecoder;
    /**
     * Constructor
     * 
     * @param callable $yamlDecoder 
     */
    public function __construct($yamlDecoder=null) {
        if (!empty($yamlDecoder)) {
            $this->setYamlDecoder($yamlDecoder);
        } else {
            if (function_exists('yaml_parse')) {
                $this->setYamlDecoder('yaml_parse');
            }
        }
    }
    /**
     * Get callback for decoding YAML
     *
     * @return callable
     */
    public function getYamlDecoder()
    {
        return $this->yamlDecoder;
    }
    /**
     * Set callback for decoding YAML
     *
     * @param  callable $yamlDecoder the decoder to set
     * @return Yaml
     */
    public function setYamlDecoder($yamlDecoder)
    {
        if (!is_callable($yamlDecoder)) {
            throw new Exception\InvalidArgumentException('Invalid parameter to setYamlDecoder() - must be callable');
        }
        $this->yamlDecoder = $yamlDecoder;
        return $this;
    }
    /**
     * fromFile(): defined by Reader interface.
     *
     * @see    Reader::fromFile()
     * @param  string $filename
     * @return array
     */
    public function fromFile($filename)
    {
        if (!file_exists($filename)) {
            throw new Exception\RuntimeException("The file $filename doesn't exists.");
        }
        if (null === $this->getYamlDecoder()) {
             throw new Exception\RuntimeException("You didn't specify a Yaml callback decoder");
        }
        
        $this->directory = dirname($filename);
        
        $config = call_user_func($this->getYamlDecoder(), file_get_contents($filename));
        if (null === $config) {
            throw new Exception\RuntimeException("Error parsing YAML data");
        }  
        
        return $this->process($config);
    }

    /**
     * fromString(): defined by Reader interface.
     *
     * @see    Reader::fromString()
     * @param  string $string
     * @return array
     */
    public function fromString($string)
    {
        if (null === $this->getYamlDecoder()) {
             throw new Exception\RuntimeException("You didn't specify a Yaml callback decoder");
        }
        if (empty($string)) {
            return array();
        }
        
        $this->directory = null;
        
        $config = call_user_func($this->getYamlDecoder(), $string);
        if (null === $config) {
            throw new Exception\RuntimeException("Error parsing YAML data");
        }   
        
        return $this->process($config);
    }
    /**
     * Process the array for @include
     * 
     * @param  array $data
     * @return array 
     */
    protected function process(array $data) {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->process($value);
            }
            if (trim($key)==='@include') {
                if ($this->directory === null) {
                    throw new Exception\RuntimeException('Cannot process @include statement for a json string');
                }
                $reader = clone $this;
                unset($data[$key]);
                $data = array_replace_recursive($data, $reader->fromFile($this->directory . '/' . $value));
            } 
        }
        return $data;
    }
}
