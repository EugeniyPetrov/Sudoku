<?php

namespace EugeniyPetrov;

class UnableToSolveException extends \Exception
{
    protected $message = 'This sudoku have no solution';
}