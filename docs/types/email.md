##Email Type

The email type uses first and last names from the database to build email address, you may pass in a list of domains and custom named params for numeric and alphnumeric subsections.

**The type has has the following options:**
1. domains - optional comma seperated list of top level domains to use
3. format  - the email format to use e.g {fname}{lname}{alpha1}@{alpha2}.{domain}

**The type may be declared as follows:**

```xml

    <datatype name="email" >
        <option name="format" value="{fname}{lname}{alpha1}@{alpha2}.{domain}" />
        <option name="alpha1" value="ccCCC"  />
        <option name="alpha2" value="cccc"  />
    <datatype>

```

This will produce an email address with the following format.

* {fname}  - a persons first name.
* {lname}  - a persons last name. 
* {alpha1} - the format "ccCCC"(alphanumeric). 
* {alpha2} - the format "cccc" (alphanumeric).
* {domain} - a random value from the domain list.

**A smiple email:**

```xml

    <datatype name="email" >
        <option name="format" value="{fname}{lname}@.{domain}" />
    <datatype>

```
**A smiple email with odd character:**

```xml

    <datatype name="email" >
        <option name="format" value="{fname}\'{lname}@.{domain}" />
    <datatype>

```

**A smiple email fo au domains:**

```xml

    <datatype name="email" >
        <option name="format" value="{fname}{lname}@.{domain}" />
        <option name="domains" value="optus.com.au,telstra.com.au" />
    <datatype>

```

**A selector can be used to mix a few formats into the column:**

```xml

    <random>
        <datatype name="email" >
            <option name="format" value="{fname}{lname}@.{domain}" />
            <option name="domains" value="optus.com.au,telstra.com.au" />
        <datatype>
        
        <datatype name="email" >
            <option name="format" value="{fname}\'{lname}@.{domain}" />
            <option name="domains" value="optus.com.au,telstra.com.au" />
        <datatype>
        
         <datatype name="email" >
            <option name="format" value="{fname}_{alaph1}_{lname}@.{domain}" />
            <option name="domains" value="optus.com.au,telstra.com.au" />
            <option name="alpha1" value="ccXXX" />
        <datatype>
    </random>

```
