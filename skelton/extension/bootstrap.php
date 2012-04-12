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
| Faker DataTypes
|--------------------------------------------------------------------------
|
*/



/*
|--------------------------------------------------------------------------
| FakerDataTypeConfig
|--------------------------------------------------------------------------
|
*/ 
    

/* End of File */