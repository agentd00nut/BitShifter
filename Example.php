<?php

include_once 'BitShifter.php';

// Setup a BS (lol) with an array of values
$bs = new BitShifter(array("a","b","c","d"));

// Get the value for the first element in the array.
$x = $bs->shift("a")->getValue();
echo "a: $x\n"; // X: 1

// Tell us the elements of the array that belong to this value.
// More than one element can be returned if the value is a combination of bits... IE. "3" will return the first and second
//      array entries.
echo "All the entries that match the value of '$x'\n";
print_r(  $bs->convertValue($x) ) ; // ['a']

// Showing a multi value return
//      Order of returned values is not guaranteed in any way... though its likely to be the reverse the input value array.;
echo "All the entries that match the value of 7\n";
print_r( $bs->convertValue(7) ); // ['c','b','a']



// Create another BS.
$bs2 = new BitShifter(array("a","b","c","d"));

// Shifting c and converting it returns c, identity.
$bs2->shift("c");
echo "bs2 value is: ".$bs2->getValue()."\n";
echo "All the entries that match the current value of bs2\n";
print_r($bs2->convert());


echo "Add bs2(containing 'c' as its shifted value) to bs(containing 'a' as its value), convert its values)\n";
echo "bs value: ".$bs->getValue()."\n";
$bs->add($bs2);
echo "bs value after adding bs2: ".$bs->getValue()."\n";
echo "All the entries in bs that match the current value of bs\n";
print_r($bs->convert());

echo "Subtract bs2('c') from bs('a')\n";
$bs->sub($bs2);
print_r($bs->convert());


echo "OverRiding the bit value for entries in the BS array is possible.  Here we tell 'a' to be the '4'th bit place holder
    and tell 'e' (previously the '4'th bit) to be the first (0)th bit holder, then we dump the mapping of values to their
    bit holders.\n";
echo "a:".$bs->a."; A is the 0th element so its bit value is 1\n";
$bs->a=4; // Set A to the 4th element
$bs->e=0; // set E to the 0th element
echo "a:".$bs->a."; We've set A to the 4th bit element so its value is 10000 ie. 16\n";
echo "The mapping of elements to their bit value, now out of order\n";
print_r($bs->getMap());