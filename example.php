<?php

require_once 'EugeniyPetrov/Sudoku.php';

/*$sudoku = new EugeniyPetrov\Sudoku(array(
    array(   2,    1, null,    4,    3, null, null,    7, null),
    array(null, null, null, null, null, null,    8,    5, null),
    array(null, null,    4, null, null,    7, null,    1,    3),
    array(null, null, null, null, null,    2, null,    9,    5),
    array(null, null, null,    8,    1,    9, null, null, null),
    array(   9,    6, null,    7, null, null, null, null, null),
    array(   8,    9, null,    5, null, null,    7, null, null),
    array(null,    2,    1, null, null, null, null, null, null),
    array(null,    3, null, null,    7,    8, null,    6,    9),
));*/

/*$sudoku = new EugeniyPetrov\Sudoku(array(
    array(0, 0, 0, 1, 0, 0, 0, 6, 0),
    array(0, 5, 0, 0, 8, 0, 0, 0, 0),
    array(0, 0, 0, 0, 7, 3, 0, 0, 0),
    array(0, 0, 3, 0, 5, 0, 0, 0, 0),
    array(0, 0, 0, 0, 0, 7, 0, 8, 9),
    array(0, 8, 0, 2, 6, 9, 0, 5, 1),
    array(7, 0, 0, 5, 3, 0, 6, 0, 2),
    array(0, 6, 0, 7, 2, 8, 0, 0, 5),
    array(0, 0, 0, 0, 0, 0, 0, 0, 0),
));*/

$sudoku = new EugeniyPetrov\Sudoku(array(
    array(6, 7, 2, 0, 0, 4, 1, 0, 5),
    array(0, 1, 0, 0, 0, 0, 0, 8, 0),
    array(0, 3, 0, 0, 0, 9, 0, 0, 2),
    array(0, 4, 5, 0, 1, 0, 0, 0, 0),
    array(0, 0, 0, 0, 4, 7, 0, 6, 8),
    array(0, 0, 0, 9, 2, 0, 0, 7, 0),
    array(0, 5, 0, 0, 0, 1, 0, 0, 0),
    array(4, 0, 0, 5, 0, 0, 0, 0, 0),
    array(0, 0, 0, 0, 9, 0, 6, 0, 0),
));

$sudoku->solve();

echo $sudoku;
