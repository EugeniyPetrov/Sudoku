<?php

namespace EugeniyPetrov;

/**
 * Class Sudoku

 * @author Eugeniy Petrov <eug.a.petrov@gmail.com>
 */
class Sudoku
{
    protected $sudoku;
    protected $rows;
    protected $cols;
    protected $squares;

    private $remains_to_solve = 81;

    /**
     * @param array $sudoku bi-dimensional sudoku grid
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $sudoku)
    {
        if (count($sudoku) != 9) {
            throw new \InvalidArgumentException;
        }

        foreach ($sudoku as $row => $cols) {
            if (count($cols) != 9) {
                throw new \InvalidArgumentException;
            }

            foreach ($cols as $col => $number) {
                if (!$number) {
                    continue;
                }
                $this->set($row, $col, $number);
            }
        }
    }

    /**
     * Get square number by row and col. Squares are counted from 0 to 9 starting from top left corner.
     *
     * @param int $row
     * @param int $col
     *
     * @return int
     */
    protected static function getSquareNum($row, $col)
    {
        return (int)(floor($col / 3) + floor($row / 3) * 3);
    }

    /**
     * Set $number as present in specific $row
     *
     * @param int $row
     * @param int $number
     *
     * @throws \InvalidArgumentException
     */
    protected function setToRow($row, $number)
    {
        isset($this->rows[$row]) or $this->rows[$row] = array();
        if (isset($this->rows[$row][$number])) {
            throw new \InvalidArgumentException('Number ' . $number . ' already exists in row ' . $row);
        }
        $this->rows[$row][$number] = true;
    }

    /**
     * Set $number as present in specific $col
     *
     * @param int $col
     * @param int $number
     *
     * @throws \InvalidArgumentException
     */
    protected function setToCol($col, $number)
    {
        isset($this->cols[$col]) or $this->cols[$col] = array();
        if (isset($this->cols[$col][$number])) {
            throw new \InvalidArgumentException('Number ' . $number . ' already exists in col ' . $col);
        }
        $this->cols[$col][$number] = true;
    }

    /**
     * Set $number as present in specific $square
     *
     * @param int $square square (counted from 0 to 9 starting from top left corner)
     * @param int $number
     *
     * @throws \InvalidArgumentException
     */
    protected function setToSquare($square, $number)
    {
        isset($this->squares[$square]) or $this->squares[$square] = array();
        if (isset($this->squares[$square][$number])) {
            throw new \InvalidArgumentException('Number ' . $number . ' already exists in square ' . $square);
        }
        $this->squares[$square][$number] = true;
    }

    /**
     * Find unspecified numbers of sudoku.
     *
     * Algorithm works by counting the number of possible numbers for each cell and setting the only possible
     * numbers in their places until sudoku is not solved. It there are no any cells with only possible values
     * trying to recursively solve sudoku with each possible values. Throws UnableToSolveException if sudoku
     * have no solutions
     *
     * @throws UnableToSolveException
     */
    public function solve()
    {
        for ($max_possible = 1; $max_possible <= 9; $max_possible++) {
            for ($row = 0; $row < 9; $row++) {
                for ($col = 0; $col < 9; $col++) {
                    if ($this->get($row, $col)) {
                        continue;
                    }

                    $possible_numbers = $this->findPossible($row, $col, $max_possible + 1);
                    if (!$possible_numbers) {
                        throw new UnableToSolveException;
                    }

                    if (count($possible_numbers) == 1) {
                        // if only one number is possible on given place - set it and begin from the start.
                        $this->set($row, $col, $possible_numbers[0]);
                        $max_possible = 0;
                        $row = 0;
                        $col = 0;
                        continue 3;
                    } elseif (count($possible_numbers) <= $max_possible) {
                        // if only many numbers is possible try each recursively until solved. Then raw-copy solved
                        // sudoku to the current one
                        foreach ($possible_numbers as $possible_number) {
                            $sudoku = clone $this;
                            $sudoku->set($row, $col, $possible_number);
                            try {
                                $sudoku->solve();
                                $this->copy($sudoku);
                                return;
                            } catch (UnableToSolveException $e) {
                                continue;
                            }
                        }

                        throw new UnableToSolveException;
                    }
                }
            }

            if ($this->isSolved()) {
                return;
            }
        }
    }

    /**
     * Checks if sudoku solved
     *
     * @return bool
     */
    public function isSolved()
    {
        return !$this->remains_to_solve;
    }

    /**
     * Set $number at cell specified by $row and $col
     *
     * @param int $row
     * @param int $col
     * @param int $number
     */
    public function set($row, $col, $number)
    {
        $this->sudoku[$row][$col] = $number;

        // index rows, cols and squares to fast searching
        $this->setToRow($row, $number);
        $this->setToCol($col, $number);
        $this->setToSquare(self::getSquareNum($row, $col), $number);

        $this->remains_to_solve--;
    }

    /**
     * Raw copy of given sudoku to the current one.
     *
     * @param Sudoku $sudoku object to copy from
     */
    public function copy(self $sudoku)
    {
        $this->sudoku = $sudoku->sudoku;
        $this->rows = $sudoku->rows;
        $this->cols = $sudoku->cols;
        $this->squares = $sudoku->squares;
        $this->remains_to_solve = $sudoku->remains_to_solve;
    }

    /**
     * Get a $number at position $row, $col
     *
     * @param int $row
     * @param int $col
     *
     * @return mixed
     */
    public function get($row, $col)
    {
        return !empty($this->sudoku[$row][$col]) ? $this->sudoku[$row][$col] : null;
    }

    /**
     * Finds all possible numbers at given position.
     *
     * @param int $row
     * @param int $col
     * @param int $limit stop find process when $limit values found.
     *
     * @return array
     */
    protected function findPossible($row, $col, $limit = 0)
    {
        $possible = array();
        for ($number = 1; $number <= 9; $number++) {
            $in_row = !empty($this->rows[$row][$number]);
            $in_col = !empty($this->cols[$col][$number]);
            $in_square = !empty($this->squares[self::getSquareNum($row, $col)][$number]);

            if (!$in_row && !$in_col && !$in_square) {
                $possible[] = $number;
                if (count($possible) == $limit) {
                    break;
                }
            }
        }
        return $possible;
    }

    /**
     * String representation of sudoku.
     *
     * @return string
     */
    public function __toString()
    {
        $str = '';
        for ($row = 0; $row < 9; $row++) {
            for ($col = 0; $col < 9; $col++) {
                $str .= $this->get($row, $col) . "\t";
            }
            $str .= "\n";
        }
        return $str;
    }
}
