<?php

namespace App\Model\Exceptions;

use Throwable;
use Exception;

// DuplicatedCodeException - исключение дублирующегося кода страны
class DuplicatedCodeException extends Exception {

    // переопределение конструктора исключения
    public function __construct(string $duplicatedCode, Throwable $previous = null) {
        $exceptionMessage = "country code '". $duplicatedCode ."' is duplicated";
        // вызов конструктора базового класса исключения
        parent::__construct(
            message: $exceptionMessage, 
            code: ErrorCodes::DUPLICATED_CODE_ERROR,
            previous: $previous,
        );
    }
}