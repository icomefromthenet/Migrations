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

use Faker\PlatformFactory;
use Faker\ColumnTypeFactory;
use Faker\Components\Faker\Formatter\FormatterFactory;
use Faker\Components\Faker\TypeFactory;
use Faker\Locale\LocaleFactory;

/*
|--------------------------------------------------------------------------
| Doctrine Platforms
|--------------------------------------------------------------------------
|
| To include new platforms must tell Faker\\PlatformFactory what the new or overriden
| extensions are.
|
|
| Override Built-in Platform (mysql):
|
|   PlatformFactory::registerExtension('mysql','Faker\\Components\\Extension\\Doctrine\\Platforms\\MySqlPlatform');
|
| Include New MyPlatform:
|
| PlatformFactory::registerExtension('myplatform','Faker\\Components\\Extension\\Doctrine\\Platforms\\MyPlatform');
|
*/

    PlatformFactory::registerExtension('mysql','Faker\\Extension\\Doctrine\\Platforms\\MySqlPlatform');

 
/*
|--------------------------------------------------------------------------
| Doctrine Column Types
|--------------------------------------------------------------------------
|
| To include new column types use the Faker\\ColumnTypeFactory
| 
|
|  Add new Column types (mysql):
|
|   ColumnTypeFactory::registerExtension('cus_array','Faker\\Components\\Extension\\Doctrine\\Type\\ArrayType');
|
| To use new column types you will need to also create a platform extension, and add the key used above to the initializeDoctrineTypeMappings()
*/ 

    //ColumnTypeFactory::registerExtension('cus_array','Faker\\Components\\Extension\\Doctrine\\Type\\ArrayType');

    
/*
|--------------------------------------------------------------------------
| Faker DataTypes
|--------------------------------------------------------------------------
| 
| To Add a new datatype a it must be registered, and the object
| are extending from base Type.
|
| You may also override built in types using the same key.
|
| Example:
|
| TypeFactory::registerExtension('vector','Faker\\Extension\\Faker\\Type\\Vector');
*/

 //TypeFactory::registerExtension('vector','Faker\\Extension\\Faker\\Type\\Vector');

 TypeFactory::registerExtension('unix-timestamp','Faker\\Extension\\Faker\\Type\\UnixTimestamp');
 
/*
|--------------------------------------------------------------------------
| Faker Formatters
|--------------------------------------------------------------------------
|
| Register a new formatter, which control how data is written to the writter.
|
| FormatterFactory::registerExtension('mongo','Faker\\Components\\Extension\\Faker\\Formatter\\Mongo');
|
*/ 
    
  //FormatterFactory::registerExtension('mongo','Faker\\Components\\Extension\\Faker\\Formatter\\Mongo');


/*
|--------------------------------------------------------------------------
| Faker Locales
|--------------------------------------------------------------------------
|
| Register a new Locale, which provide locale specific text to the generators.
|
| LocaleFactory::registerExtension('french','Faker\\Components\\Extension\\Locale\\FrenchLocale');
|
*/ 

  //LocaleFactory::registerExtension('french','Faker\\Components\\Extension\\Locale\\FrenchLocale');

/* End of File */