##City Type

The city type will provide city names parsed from [GeoNames](http://download.geonames.org), I have used the cities with population > [150000 file](http://download.geonames.org/export/dump/cities15000.zip), each city is real, a futher list of country codes may be passed into the type to restrict the city list.

**The type has the following options:**

1. countries: a comma seperated list of countries to limit the query.

**The type may be used as follows:**

```xml
    <datatype name="city">
        <option name="countries" value="AU,GB"
    </datatype>
```
The above would produce city names from the England and Australia.

The country codes are the two letter [ISO_3166-1](http://en.wikipedia.org/wiki/ISO_3166-1) country codes.

**The type could also be implemented using the following:**

```xml
    <random>
        <datatype name="city">
            <option name="countries" value="GB"
        </datatype>
        <datatype name="city">
            <option name="countries" value="AU"
        </datatype>
    </random>
```
The above would produce city names from the England and Australia.