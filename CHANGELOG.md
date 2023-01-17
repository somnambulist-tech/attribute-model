Change Log
==========

2023-01-16
----------

 * require PHP 8.1+

2022-07-11
----------

 * remove unnecessary docblocks
 * clean up method signatures
 * switch to PHP8 promoted properties
 * add `JsonArrayCaster` for casting JSON strings to simple array
 * add `EnumCaster` for casting to PHP native enums (requires PHP 8.1)

2021-05-05 - 2.0.2
------------------

 * fix handling missing keys in json caster

2021-05-05 - 2.0.1
------------------

 * fix bug in JsonCollectionCaster not handling arrays of already decoded data 

2021-01-21 - 2.0.0
------------------

 * PHP 8 support
 * update to domain 4.0 and collection 5.0

2020-09-22 - 1.0.2
------------------

 * fix bug in `EnumberableValueCaster` where int constant values may be compared as strings

2020-09-10 - 1.0.1
------------------

 * fix bugs in casters not checking for presence / null values

2020-09-07 - 1.0.0
------------------

 * initial release
