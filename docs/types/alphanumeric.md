##AlpahNumeric Type

The alphanumeric is a favourite to generate small random blocks of text for items like names , locations and other descriptions.

**There is a small DSL that provieds limited control on the output.**
```
1.   C, c, E - any consonant (Upper case, lower case, any)
2.   V, v, F - any vowel (Upper case, lower case, any)
3.   L, l, D - any letter (Upper case, lower case, any)
4.   X       - 1-9
5.   x       - 0-9
6.   H       - 0-F
```

**The format CcVDx would give:**

```
'C' = Upper case consonant
'c' = Lower case consonant
'V' = Upper case vowel
'D' = Any letter on random case
'x' = a number between 0-9
```

**To declare a alphanumeric type:**
```xml
<datatype name="alphanumeric">
    <option name="format" value="CcVDx" />
</datatype>
```

The only option is the **format** which is required.

If your looking to combine a prefix you can use the constant datatype as shown below.

```xml
<datatype name="constant">
    <option name="value" value="Index_" />
</datatype>
<datatype name="alphanumeric">
    <option name="format" value="CcVDx" />
</datatype>
```

Would give return for example 'Index_FgEp6'.  


