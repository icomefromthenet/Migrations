##Date and DateTime Type

This type will enables the definition of a date, with each loop having an optional modify applied with a test to an optional max causing a reset (date-range). The doctine database platform will format the DateTime for your database.  A date column will receive a date and time column the time.

Do not use this type for a timestamp, use timestamp type instead.

**This type has the following options:**

1. start  - The strtotime starting date.
2. max    - Optional strtotime max date.
3. modify - Optional strtotime modify string.

**The type may be declared as follows:**

today = date and time that the generate command was run.

```xml
    <datatype>
        <option name="start" value="today" />
        <option name="modify" value="+1 week" />
        <option name="max" value="today +10 weeks" />
    </datatype>
```

The above would give the starting date as today would increment the date by 1 week on each loop and when date becomes greater than 10 weeks it will reset back to today.

```xml
    <datatype>
        <option name="start" value="today" />
        <option name="modify" value="+1 week" />
    </datatype>
```

The above would contine to increment with no max set.

Be careful when not specifing a max, example if the the number of rows to generte increases from 100 to 1 million then the last row would have a date of 1 million +weeks into the future. 

```xml
    <datatype>
        <option name="start" value="today" />
    </datatype>
```

The above would be fixed to today on every loop.