PROBLEM 1 (makeSchedule)
===================================================================================

Implement a function with the following signature:

    function makeSchedule(array $todo): array

The input array should be interpreted as a list of tasks to be done, together with
dependencies on other tasks, for example:

    [
        0 => [1, 2],
        1 => [2, 3],
        2 => [3],
        3 => []
    ]

This should be interpreted as follows:

  - Task 0 should be performed only after tasks 1 and 2 are done.
  - Task 1 should be performed only after tasks 2 and 3 are done.
  - Task 2 should be performed only after task 3 is done.
  - Task 3 may be performed at any time.

The input array is guaranteed to be non-empty, and tasks are guaranteed to be
indexed from 0 to count($todo) - 1.  Every element of the input array is also
guaranteed to be an array, all elements of each are guaranteed to be valid task
indices.  You may assume that the input is always correct, so you do not need to
validate it.

The function should return an array listing the sequence of tasks in the order of
execution such that all the constraints provided as an input are satisfied.  In
case this is impossible, the function should return an empty array.  For example,
the input above enforces just a single possible sequence of task execution:

    [3, 2, 1, 0]

Note that in some cases multiple different schedules may satisfy all the
constraints.  E.g., for the following input:

    [
        0 => [],
        1 => []
    ]

Both [0, 1] and [1, 0] are valid results, and both would be accepted by the
automated grader.  A simple example of input containing constraints that cannot be
satisfied would be:

    [
        0 => [1],
        1 => [0]
    ]

makeSchedule() should return an empty array when called with the above array as an
argument.

An efficient solution should be capable of processing a few hundred tasks with
several thousand dependencies (in total) in under a second.



PROBLEM 2 (costSavings)
===================================================================================

Implement a function with the following signature:

    function costSavings(int $numCities, array $flights): int

The input describes the operational costs of an airline operating several flights
between a number of cities.  For simplicity, cities are denoted by integer numbers
from 0 to $numCities - 1.  The $flights array describes routes and costs in the
following format:

    [
        ['from' => 0, 'to' => 1, 'cost' => 3],
        ['from' => 2, 'to' => 0, 'cost' => 1],
        ['from' => 2, 'to' => 3, 'cost' => 1],
        ['from' => 1, 'to' => 3, 'cost' => 2],
    ]

Each array entry describes a single route between cities denoted by the indices in
the 'from' and 'to' fields.  Operational costs are listed as a positive integer
number in the 'cost' field.

For simplicity, assume that all flights are bidirectional (so that the first
element of the array above describes both the flight from city 0 to city 1, and
the flight from city 1 to city 0).  We will also assume that there are no shared
operational costs for different flights.

The airline claims that each of the cities it currently serves is reachable from
every other city that it also serves using only flights operated by this same
airline.  (For example, given the input above, it's possible to get from city 0 to
city 3 first by taking a flight from city 0 to city 1, and then a flight from city
1 to city 3; using city 2 instead of city 1 as a layover point is also possible.)

However, the company wants to cut its operational costs while preserving this
property of every city being reachable from every other city.  costSavings() should
compute the maximum possible savings for the airline.

Given the input above (with $numCities = 4), costSavings() should return 3 as a
result.  The flight between cities 0 and 1 can be dropped, thus reducing the
operational costs by 3, and no other flight can be dropped after that.

If we use the following $flights array as an input (still with $numCities = 4):

    [
        ['from' => 0, 'to' => 1, 'cost' => 3],
        ['from' => 2, 'to' => 0, 'cost' => 1],
        ['from' => 2, 'to' => 3, 'cost' => 1],
        ['from' => 1, 'to' => 3, 'cost' => 2],
        ['from' => 2, 'to' => 1, 'cost' => 1],
    ]

...costSavings() should return 5 as a result.

Note that there can be multiple flights connecting the same pair of cities, each
with its own operational costs.  In that case, the airline would definitely want
to get rid of all of them except for the cheapest one (and perhaps it would be
economical to get rid of that one, too).  E.g., with $numCities = 2:

    [
        ['from' => 0, 'to' => 1, 'cost' => 3],
        ['from' => 1, 'to' => 0, 'cost' => 1],
        ['from' => 0, 'to' => 1, 'cost' => 2],
    ]

...costSavings() should return 5.

In case the input array does not describe a set of routes that connect all the
cities, costSavings() should return -1 as a result.  For example, calling
costSavings() with any of the above arrays, but $numCities = 5 should produce -1 as
a result.  (Because there would be no flights connecting city 4 to any of the other
cities.)

All inputs are guaranteed to be valid, so you do not need to verify whether city
indices and operational costs lie within the bounds specified above.

You also do not need to concern yourself with issues of integer precision, all the
inputs supplied to your function will ensure that the final result and any
intermediate results fit into 64-bit integers.

From efficiency standpoint, your solution should be able to process a set of
flights connecting a couple of hundred cities, with each city being directly
connected to every other city, within no more than a few seconds.



PROBLEM 3 (bestHit)
===================================================================================

Implement a function with the following signature:

    function bestHit(string $text, string $query): array

The function should implement a fairly simple form of full text search.  First
argument passed to the function will contain the document to be searched, while the
second argument should be interpreted as a list of words to be found in the
document.  bestHit() should return an array consisting of the starting and ending
indices of the shortest substring in the $text that contains all the words in
$query.  Both indices should be zero-indexed and inclusive.  If no match could be
found, bestHit() should return an empty array.

Importantly:

1. Only Latin alphabetic characters (twenty six letters from A to Z in both upper
   and lower case) are considered to be parts of words.  Any other characters may
   be present in the inputs, but should be treated as whitespace:

    bestHit('some,random-text', 'random1text') === [5, 15]

   Here the document is considered to consist of the words 'some', 'random' and
   'text', while the query is interpreted as asking for words 'random' and 'text'.

2. Search should be case-insensitive:

    bestHit('MATCHES', 'matches') === [0, 6]

3. Search should respect word boundaries:

    bestHit('some,random-text', 'random1tex') === []

   Here the word 'tex' in the query does not match the word 'text' in the document.

4. Note that word order in the query is not important, bestHit() should find
   the shortest substring in the document that contains all of the search terms in
   any order:

    bestHit('a b... a', 'b a') === [0, 2]

5. Multiple non-alphabetic characters in a row should be considered as a single
   word separator, but bestHit() must return the indices that could be used to
   extract the desired substring from the original, untransformed $text:

    bestHit('Oh, ok then!', 'OK.  ...Oh.') === [0, 5]

6. Multiple occurences of the same word in the query should be interpreted as a
   request to find a substring that contains at least that many instances of the
   word in question:

    bestHit('that is fine, totally', 'Fine, FINE') === []
    bestHit('that is fine, totally fine.', 'Fine, FINE') === [8, 25]

7. bestHit() should find the shortest substring containing all the search words
   in sufficient quantities in terms of the numbers of characters in this
   substring, and not in the sense of the number of words in it, or any other
   metric.

    bestHit('a b a... a', 'a a') === [0, 4]

   ('a b a' contains three words, but only five characters, as opposed to
   'a... a', which contains only two words, but six characters.)

8. In case there are multiple hits of the same length in the document, bestHit()
   should return indices pointing to the first occurrence:

    bestHit('a a', 'a') === [0, 0]

You may safely assume that both the document and the query will contain at least
one word, so you do not need to sanely handle the case when either of them doesn't.

Your implementation should be fairly efficient, as it will be expected to handle
a few hundred potentially large queries against ~40Kb documents in under ten
seconds.
