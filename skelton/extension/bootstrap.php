<?php
/*
|--------------------------------------------------------------------------
| Extension Bootstrap
|--------------------------------------------------------------------------
|
| To use extension, please register each class with their individual factories
| examples are show below:
|
| Override Built-in Platform
|
|
*/

use Migration\PlatformFactory;
use Migration\ColumnTypeFactory;
use Migration\Components\Faker\Formatter\FormatterFactory;
use Migration\Components\Faker\TypeConfigFactory;

/*
|--------------------------------------------------------------------------
| Doctrine Platforms
|--------------------------------------------------------------------------
|
| To include new platforms must tell Migration\\PlatformFactory what the new or overriden
| extensions are.
|
|
| Override Built-in Platform (mysql):
|
|   PlatformFactory::registerExtension('mysql','Migration\\Components\\Extension\\Doctrine\\Platforms\\MySqlPlatform');
|
| Include New MyPlatform:
|
| PlatformFactory::registerExtension('myplatform','Migration\\Components\\Extension\\Doctrine\\Platforms\\MyPlatform');
|
*/

    PlatformFactory::registerExtension('mysql','Migration\\Components\\Extension\\Doctrine\\Platforms\\MySqlPlatform');

 
/*
|--------------------------------------------------------------------------
| Doctrine Column Types
|--------------------------------------------------------------------------
|
| To include new column types use the Migration\\ColumnTypeFactory
| 
|
|  Add new Column types (mysql):
|
|   ColumnTypeFactory::registerExtension('cus_array','Migration\\Components\\Extension\\Doctrine\\Type\\ArrayType');
|
| To use new column types you will need to also create a platform extension, and add the key used above to the initializeDoctrineTypeMappings()
*/ 

    //ColumnTypeFactory::registerExtension('cus_array','Migration\\Components\\Extension\\Doctrine\\Type\\ArrayType');

    
/*
|--------------------------------------------------------------------------
| Faker Config DataTypes
|--------------------------------------------------------------------------
| 
| To Add a new datatype a config object must be registered, the config object
| implements \\Migration\\Components\\Faker\\TypeConfigInterface.
|
| You are only required to register the config as it containsa factory for the partner datatype. 
|
| You may also override built in types using the same key.
|
| Example:
|
| TypeConfigFactory::registerExtension('vector','Migration\\Components\\Extension\\Faker\\Config\\Vector');
*/

 //TypeConfigFactory::registerExtension('vector','Migration\\Components\\Extension\\Faker\\Config\\Vector');

/*
|--------------------------------------------------------------------------
| Faker Formatters
|--------------------------------------------------------------------------
|
| Regiater a new formatter, which control how data is written to the writter
|
| FormatterFactory::registerExtension('mongo','Migration\\Components\\Extension\\Faker\\Formatter\\Mongo');
|
*/ 
    
  //FormatterFactory::registerExtension('mongo','Migration\\Components\\Extension\\Faker\\Formatter\\Mongo');
   

/* End of File */