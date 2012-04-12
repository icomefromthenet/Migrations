<?php
namespace Migration\Components\Faker;

use Symfony\Component\Config\Definition\ConfigurationInterface;

interface TypeConfigInterface extends ConfigurationInterface
{
    
    public function __construct(Utilities $util);
    
    public function merge($config);
    
    public function build();
    
}
/* End of File */