##Range Type

The range is suited to generate a series of values that increase linearly, where the auto increment type will continue to grow indefinitely the range accepts a max and will restart when achieved.

**The range type has the following options:**

1. max  - The highest possible value.
2. min  - The smallest value.
3. step - The value to increment on each loop.

The range returns numeric values i.e. not return a string.

Please be aware of perision issues with [floating point numbers](http://php.net/manual/en/language.types.float.php).

**To declare a range use the following format:**

```xml

<datatype name="range">
    <option name="max" value="100" />
    <option name="min" value="1" />
    <option name="step" value="5" />
</datatype>
```

