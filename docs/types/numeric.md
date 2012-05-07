##Numeric Type

The numeric type can be used to represet numbers in specify formats. It will generate a random integer for each placeholder. Please be mindful of formats that are not valid PHP int, floats, doubles. Values that exceed the rangae offered by php should be generated with the alphanumeric which will cast value as string.

**The numeric type has the following option.**

1. format - the placeholder to replace for example xxxxxxxxxx.xxx.

**To declare this type use the following format:**

```xml

<datatype>
    <option name="format" value="xxxx" />
</datatype>

```

**A decimal my also be included:**

```xml

<datatype>
    <option name="format" value="xxxx.xx" />
</datatype>

```

To Represent a block of formats all values up to a million using the random selector is recommended.


```xml

<random>
    <!-- 0 - 10 cents --> 
    <datatype>
        <option name="format" value="0.0x" />
    </datatype>

    <!-- 0 - 100 cents -->
    <datatype>
        <option name="format" value="0.xx" />
    </datatype>

    <!-- 1.00 - 9.99 dollars -->
    <datatype>
        <option name="format" value="x.xx" />
        
    <!-- 10.00 dollars - 99.99 dollars -->
    <datatype>
        <option name="format" value="xx.xx" />
    </datatype>
    
    <!-- 100.00 hundred - 999.99 hundred -->
    <datatype>
        <option name="format" value="xxx.xx" />
    </datatype>

    <!-- 1,000.00 thousand - 9,000 dollars -->
    <datatype>
        <option name="format" value="xxxx.xx" />
    </datatype>

    <!-- 10,000.00 thousand - 99,999.99 thousand -->
    <datatype>
        <option name="format" value="xxxxx.xx" />
    </datatype>

    <!-- 100,000.00 thousand - 999,999.99 thousand -->
    <datatype>
        <option name="format" value="xxxxxx.xx" />
    </datatype>

    <!-- 1,000,000.00 million - 9,999,999.99 million -->
    <datatype>
        <option name="format" value="xxxxxxx.xx" />
    </datatype>
</random>

```



