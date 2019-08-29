#BitShifter

Load an array of keys / flags into a bitShifter. `$bs = new BitShifter(array("young","old","tall","short"));`

Find out what the bit value of a combination of those keys / flags would be by shifting them onto the
BitShifters value.  `$bs->shift("tall")->shift("young")->getValue(); // 12`

Given a bit value its easy to find out what keys / flags the value translates into. 
`$bs->convertValue(7); // ['c','b','a']`

As long as you maintain a consistent array of flags it's easy to shift data into and out of integer values...
IE. you can store one value for your flags in a database instead of a column for each flag.

It's also trivial to extend an array as long at the earlier elements don't change!

# Adding and Subtracting.

You can also add and subtract two BitShifters by passing one to anothers `add` or `subtract` method.
This works in place on the receiving BitShifter and modifies its value...
```
$bs = new BitShifter(array("young","old","tall","short"));
$bs->shift("young");

$bs2 = new BitShifter(array("young","old","tall","short"));
$bs2->shiftByArray(["young","short"]);

echo $bs->add($bs2)->getValue()."\n";; // 9... 'young'=1 'short'=8... young&short == 9
```

Adding also removes all the duplicate flags between the two objects.

Subtraction works much the same way, the flags set in the BitShifter being passed in will be removed from
the receiving BitShifters flags.

It's important that the maps of the two BitShifters are the same when adding and subtracting.
We check that the keys exist within one anothers map but not if the values (the position of the key in the map)
are the same.

# Isn't there a better way to do this?

Almost definitely but this works well enough for me and was fun to write.
