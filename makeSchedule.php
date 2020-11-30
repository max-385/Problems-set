<?php

function makeSchedule(array $todo): array
{
    $sequence = [];

    while ($todo) {
        $isChanged = false; //set flag before loop

        foreach ($todo as $taskNumber => $tasks) {
            $intersect = array_intersect($tasks, $sequence);
            if (empty($tasks) || $intersect === $tasks) { // task doesn't have dependencies
                $sequence[] = $taskNumber; // perform it
                unset($todo[$taskNumber]);
                $isChanged = true;
            }
        }

        if ($isChanged === false) {  // nothing changes, loop is endless
            return $sequence = [];
        }
    }

    return $sequence;
}
