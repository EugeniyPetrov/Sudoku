<?php

require_once 'EugeniyPetrov/Sudoku.php';

$sudoku = new EugeniyPetrov\Sudoku(array(
    array(   2,    1, null,    4,    3, null, null,    7, null),
    array(null, null, null, null, null, null,    8,    5, null),
    array(null, null,    4, null, null,    7, null,    1,    3),
    array(null, null, null, null, null,    2, null,    9,    5),
    array(null, null, null,    8,    1,    9, null, null, null),
    array(   9,    6, null,    7, null, null, null, null, null),
    array(   8,    9, null,    5, null, null,    7, null, null),
    array(null,    2,    1, null, null, null, null, null, null),
    array(null,    3, null, null,    7,    8, null,    6,    9),
));

$sudoku->solve();

echo $sudoku;
