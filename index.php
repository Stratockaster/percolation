<?php

require_once 'QuickFindUf.php';
require_once 'Percolation.php';

$n = 1000;

$percolation = new Percolation($n);

$usedCombinations = [];

$openingCount = 0;
while (!$percolation->percolates()) {
    $row = random_int(1, $n);
    $col = random_int(1, $n);

    if (!$percolation->isOpen($row, $col)) {
        $percolation->open($row, $col);

        $openingCount++;
//        echo 'opened: ' . $openingCount . PHP_EOL;
    }
}

echo 'percolates!' . PHP_EOL;

echo 'result: ' . $openingCount * 100 / ($n * $n) . ' %';