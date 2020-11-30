<?php

function bestHit(string $text, string $query): array
{
    $text = strtolower($text);
    $query = strtolower($query);
    $formattedQuery = explode(' ', trim(preg_replace('/[^A-Za-z]+/', ' ', $query)));
    $result = [];
    $possibleMin = count($formattedQuery) - 2;

    foreach ($formattedQuery as $wordKey => $queryWord) { // Sorted query performing
        $regex = '/(?<![a-z])' . $queryWord . '(?![a-z])/i';
        preg_match_all($regex, $text, $match, PREG_OFFSET_CAPTURE);
        if (!empty($match[0])) {    // if word is present in the text
            $possibleMin += strlen($queryWord);
            foreach ($match[0] as $foundWord) {
                $sortedByPosition[$foundWord[1]] = $foundWord[0];
            }
        } else {
            return $result;
        }
    }

    if (count($formattedQuery) == 1) { // If only 1 word in query, return first match
        $wordLength = strlen($formattedQuery[0]) - 1;
        $firstKey = array_key_first($sortedByPosition);
        return $result = [$firstKey, $firstKey + $wordLength];
    }

    $min = PHP_INT_MAX;
    $lastKey = count($formattedQuery) - 1;
    ksort($sortedByPosition);

    foreach ($sortedByPosition as $num => $item) {
        $queryForLoop = $formattedQuery;

        foreach ($sortedByPosition as $position => $word) { // All possible variations
            if (in_array($word, $queryForLoop)) {
                if (isset($variations[$num]) && count($variations[$num]) == $lastKey) {
                    $variations[$num][] = $position + strlen($word) - 1;
                } else {
                    $variations[$num][] = $position;
                }
                $key = array_search($word, $queryForLoop);
                unset($queryForLoop[$key]);
            }
            if (empty($queryForLoop)) { // Words from query are found. Make next variation
                break;
            }
        }

        if (!empty($queryForLoop)) { // Can't find at least 1 word, no more variations
            unset($variations[$num]);
            break;
        }
        $length = $variations[$num][$lastKey] - $variations[$num][0];
        if ($length < $min) {
            $min = $length; // Save shortest length
            $result = [$variations[$num][0], $variations[$num][$lastKey]];
            if ($min === $possibleMin) { // If found length can't be less
                return $result;
            }
        }
        unset ($sortedByPosition[$num]);
    }
    return $result;
}