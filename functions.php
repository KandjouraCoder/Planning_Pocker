<?php
function calculerMoyenne(array $votes): float {
    $votesNumeriques = array_filter($votes, 'is_numeric');

    if (count($votesNumeriques) === 0) {
        return 0;
    }

    return array_sum($votesNumeriques) / count($votesNumeriques);
}
