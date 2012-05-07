##AutoIncrement Type

The AutoIncrement type is perfect for representing auto-incrementing primary keys, it also can be used for other numeric ranges. The type will return a numeric value. Unlike the range type there is no setting for upper limit.

**The AutoIncrement type has the folllowing options:**

1. Start     - The numeric starting value.
2. Increment - A numeric value to grow on each loop

**The AutoIncrement can be declared as flows:**

```xml
<datatype name="autoincrement">
    <option name="start" value="1" />
    <option name="increment" value="1" />
</datatype>
```

This is the best format for a auto incrementing primary key, the first value will be 1,2,3,4,etc.


```xml
<datatype name="autoincrement">
    <option name="start" value="1" />
    <option name="increment" value="1.5" />
</datatype>
```

The above will return a non integer value, but still a numeric for example the first value will be 1, 2.5 , 4 , 5.5, etc

