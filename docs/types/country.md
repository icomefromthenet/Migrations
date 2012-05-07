##Country Type

The country type will return a random country, an optional list of The country codes using the two letter [ISO_3166-1](http://en.wikipedia.org/wiki/ISO_3166-1) format can be passed to restrict the country returned.


**The country type has the following options:**

1. countries - a comma seperated list of ISO_3166-1 country codes.

** The type may be declared as follows:**

```xml

    <datatype name="country">
    </datatype>

```

The above will return a country from the full list.


```xml

    <datatype name="country">
        <option name="countries" value="AU,GB,US" />
    </datatype>

```

The above will return country names Australia,England,United States.

