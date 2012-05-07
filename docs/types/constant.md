##Constant_String and Constant_Number Type

As their names suggests a constant will return the same value for every loop. The ConstantString will always return a string the ConstantNumber will return a numeric value.

A constant must only be used with string and numbers, value types like datatime and other primitativies like boolean and null have their own specific datatypes.

**The constant has the following options:**

1. value - The value to return.

**The constant can be declared with the following:** 

```xml
<datatype name="constant_numer">
    <option name="value" value="100" />
</datatype>
```

The above will return and integer 100 on each loop

```xml
<datatype name="constant_string">
    <option name="value" value="100" />
</datatype>
```

The above will return the string '100' not an integer.

