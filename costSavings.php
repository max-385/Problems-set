<?php

function costSavings(int $numCities, array $flights): int
{
    $savings = 0;

    array_walk($flights, function (&$flight) { // Swap 'to' and 'from' values, if 'from' > 'to'
        if ($flight['from'] > $flight['to']) {
            $tmp = $flight['from'];
            $flight['from'] = $flight['to'];
            $flight['to'] = $tmp;
        }
    });

    foreach ($flights as $key => ['from' => $from, 'to' => $to, 'cost' => $cost]) { // Remove duplicates
        if (!isset($index[$from . $to])) {
            $index[$from . $to] = ['cost' => $cost, 'key' => $key];
        } else {
            if ($index[$from . $to]['cost'] <= $cost) {
                $savings += $cost;
                unset ($flights[$key]);
            } else {
                $savings += $index[$from . $to]['cost'];
                unset ($flights[$index[$from . $to]['key']]);
                $index[$from . $to] = ['cost' => $cost, 'key' => $key];
            }
        }
    }


    usort($flights, function ($flight1, $flight2) { // Sort 'cost' descending
        return $flight2['cost'] <=> $flight1['cost'];
    });
    $arrLength = count($flights);
    $visited = [0];

    for ($i = 0; $i < $arrLength;) {
        $isChanged = false; //set flag before loop

        foreach ($flights as ['from' => $from, 'to' => $to]) { //Check is the destination available
            if (isset($visited[$from]) && !isset($visited[$to])) {
                $visited[$to] = $to;
                $isChanged = true;
                if (count($visited) === $numCities) {
                    break;
                }
            } elseif (!isset($visited[$from]) && isset($visited[$to])) {
                $visited[$from] = $from;
                $isChanged = true;
                if (count($visited) === $numCities) {
                    break;
                }
            }
        }

        $isCountEqual = count($visited) === $numCities;
        if ($isChanged === true && !$isCountEqual) {
            continue;
        } elseif ($isCountEqual) { // All the cities are reachable
            if (isset($removable)) {
                $savings += $removable['cost'];
            }
            if (count($flights) === $numCities - 1) { // Reached minimum possible flights count
                return $savings;
            }
        } elseif ($isChanged === false) { // Not allowed to remove this flight
            if (!isset($removable)) {   // If nothing has been removed
                return $savings = -1;
            }
            $flights[] = $removable;    // Return removed back to flights
        }

        $visited = [0];
        $removable = $flights[$i]; // Take most expensive flight and remember it before check
        unset($flights[$i]);
        $i++;
    }

    return $savings;
}
