Sudoku
======

Sudoku solver library. Example of usage:

```php
use \EugeniyPetrov\Sudoku;

$sudoku = new Sudoku([
    [0, 0, 0, 0, 0, 0, 0, 0, 0],
    [0, 0, 0, 0, 0, 0, 0, 0, 0],
    [0, 0, 0, 0, 0, 0, 0, 0, 0],
    [0, 0, 0, 0, 0, 0, 0, 0, 0],
    [0, 0, 0, 0, 0, 0, 0, 0, 0],
    [0, 0, 0, 0, 0, 0, 0, 0, 0],
    [0, 0, 0, 0, 0, 0, 0, 0, 0],
    [0, 0, 0, 0, 0, 0, 0, 0, 0],
    [0, 0, 0, 0, 0, 0, 0, 0, 0],
]);

$sudoku->solve();

echo $sudoku;
```

The result will be
```
1	2	3	4	5	6	7	8	9	
4	5	6	7	8	9	1	2	3	
7	8	9	1	2	3	4	5	6	
2	3	1	6	7	4	8	9	5	
8	7	5	9	1	2	3	6	4	
6	9	4	5	3	8	2	1	7	
3	1	7	2	6	5	9	4	8	
5	4	2	8	9	7	6	3	1	
9	6	8	3	4	1	5	7	2	
```

Algorithm works by counting the number of possible numbers for each cell and setting     the only possible numbers in their places until sudoku is not solved. It there are no any cells with only possible values trying to recursively solve sudoku with each possible values.

Throws UnableToSolveException if sudoku have no solutions.
