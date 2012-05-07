##People Type

The people type will select from 16,000 unique names one random set per loop iteration. A format can be passed into the type to control the order.

**The type has the following options:**

1. format - the order to print the names example '{fname} {inital} {lname}'


**The type may be declared as follows:**

```xml
    
    <datatype name="people" >
        <option name="format" value="{fname} {inital} {lname}" />
    </datatype>

```

This will produce a name like __Michael R Greenway__.

**The format can have commas etc.**

```xml
    
    <datatype name="people" >
        <option name="format" value="{fname},{inital} {lname}" />
    </datatype>

```

This will produce a name like __Michael,R Greenway__.


> Some of the names have a **' character** as the **middle inital**.


