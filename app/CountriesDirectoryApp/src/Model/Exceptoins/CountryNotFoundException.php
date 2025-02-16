<?php

namespace App\Model\Exceptions;

use Throwable;
use Exception;

// CountryNotFoundException - исключение не найденной страны
class CountryNotFoundException extends Exception {

    // переопределение конструктора исключения
    public function __construct($notFoundCode, Throwable $previous = null) {
        $exceptionMessage = "country '". $notFoundCode ."' not found";
        // вызов конструктора базового класса исключения
        parent::__construct(
            message: $exceptionMessage, 
            code: ErrorCodes::NOT_FOUND_ERROR,
            previous: $previous,
        );
    }
}