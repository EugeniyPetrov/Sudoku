<?php

namespace EugeniyPetrov;

class Sudoku {
    protected $_sudoku;
    protected $_rows;
    protected $_cols;
    protected $_squares;

    private $_remains_to_solve = 81;

    public function __construct(array $sudoku) {
        if (count($sudoku) != 9) {
            throw new \InvalidArgumentException;
        }

        foreach ($sudoku as $row => $cols) {
            if (count($cols) != 9) {
                throw new \InvalidArgumentException;
            }

            foreach ($cols as $col => $number) {
                if (!$number) continue;
                $this->set($row, $col, $number);
            }
        }
    }

    protected static function _getSquareNum($row, $col) {
        return floor($col / 3) + floor($row / 3) * 3;
    }

    protected function _setToRow($row, $number) {
        isset($this->_rows[$row]) or $this->_rows[$row] = array();
        if (isset($this->_rows[$row][$number])) {
            throw new \InvalidArgumentException('Number ' . $number . ' already exists in row ' . $row);
        }
        $this->_rows[$row][$number] = true;
    }

    protected function _setToCol($col, $number) {
        isset($this->_cols[$col]) or $this->_cols[$col] = array();
        if (isset($this->_cols[$col][$number])) {
            throw new \InvalidArgumentException('Number ' . $number . ' already exists in col ' . $col);
        }
        $this->_cols[$col][$number] = true;
    }

    protected function _setToSquare($square, $number) {
        isset($this->_squares[$square]) or $this->_squares[$square] = array();
        if (isset($this->_squares[$square][$number])) {
            throw new \InvalidArgumentException('Number ' . $number . ' already exists in square ' . $square);
        }
        $this->_squares[$square][$number] = true;
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
    public function solve() {
        for ($max_possible = 1; $max_possible <= 9; $max_possible++) {
            for ($row = 0; $row < 9; $row++) {
                for ($col = 0; $col < 9; $col++) {
                    if ($this->get($row, $col)) {
                        continue;
                    }

                    $possible_numbers = $this->_findPossible($row, $col, $max_possible + 1);
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

    public function isSolved() {
        return !$this->_remains_to_solve;
    }

    public function set($row, $col, $number) {
        $this->_sudoku[$row][$col] = $number;

        // index rows, cols and squares to fast searching
        $this->_setToRow($row, $number);
        $this->_setToCol($col, $number);
        $this->_setToSquare(self::_getSquareNum($row, $col), $number);

        $this->_remains_to_solve--;
    }

    /**
     * Raw copy of given sudoku to the current one.
     *
     * @param Sudoku $sudoku
     */
    public function copy(self $sudoku) {
        $this->_sudoku = $sudoku->_sudoku;
        $this->_rows = $sudoku->_rows;
        $this->_cols = $sudoku->_cols;
        $this->_squares = $sudoku->_squares;
        $this->_remains_to_solve = $sudoku->_remains_to_solve;
    }

    public function get($row, $col) {
        return !empty($this->_sudoku[$row][$col]) ? $this->_sudoku[$row][$col] : null;
    }

    protected function _findPossible($row, $col, $limit = 0) {
        $possible = array();
        for ($number = 1; $number <= 9; $number++) {
            $in_row = !empty($this->_rows[$row][$number]);
            $in_col = !empty($this->_cols[$col][$number]);
            $in_square = !empty($this->_squares[self::_getSquareNum($row, $col)][$number]);

            if (!$in_row && !$in_col && !$in_square) {
                $possible[] = $number;
                if (count($possible) == $limit) {
                    break;
                }
            }
        }
        return $possible;
    }

    public function __toString() {
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
