<?php

namespace App\Model;

use App\Model\Exceptions\CountryNotFoundException;
use App\Model\Exceptions\InvalidCodeException;
use App\Model\Exceptions\DuplicatedCodeException;

// CountryScenarios - класс  сметодами работы с объектами страны
class CountryScenarios {

    public function __construct(
        private readonly CountryRepository $repository
    ) {}

    // getAll - получение всех стран
    // вход: -
    // выход: список объектов Country
    public function getAll(): array {
        return $this->repository->selectAll();
    }

    // get - получение страны по коду
    // вход: код страны
    // выход: объект извлеченной страны
    // исключения: InvalidCodeException, CountryNotFoundException
    public function get(string $code) : Country {
        // выполнить проверку корректности кода
        if (!$this->validateCode($code)) {
            throw new InvalidCodeException($code, 'validation failed');
        }
        // если валидация пройдена, то получить аэропорт по данному коду
        $country = $this->repository->selectByCode($code);
        if ($country === null) {
            // если аэропорт не найден - выбросить ошибку
            throw new CountryNotFoundException($code);
        }
        //  вернуть полученный аэропорт
        return $country;
    }

    // store - добавление страны
    // вход: объект страны
    // выход: -
    // исключения: InvalidCodeException, DuplicatedCodeException
    public function store(Country $country): void {
        // выполнить проверку корректности кода
        if (!$this->validateCode(code: $country->isoAlpha2)) {
            throw new InvalidCodeException(
                invalidCode: $country->isoAlpha2, 
                message: 'validation failed',
            );
        }
        // выполнить проверку уникальности кода
        $sameCodeCountry = $this->repository->selectByCode(code: $country->isoAlpha2);
        if ($sameCodeCountry != null) {
            throw new DuplicatedCodeException(duplicatedCode: $sameCodeCountry->isoAlpha2);
        }
        // если все ок, то сохранить страну в БД
        $this->repository->save(country: $country);
    }

    // edit - редактирование страны по коду
    // вход: код редактируемой страны (не обновленный)
    // выход: -
    // исключения: InvalidCodeException, CountryNotFoundException, DuplicatedCodeException
    public function edit(string $code, Country $country): void  {
        // выполнить проверку корректности кода (до и после редактирования)
        if (!$this->validateCode(code: $code)) {
            throw new InvalidCodeException(invalidCode: $code, message: 'validation failed');
        }
        // выполнить проверку корректности отредактированного кода
        if (!$this->validateCode(code: $country->isoAlpha2)) {
            throw new InvalidCodeException(invalidCode: $country->isoAlpha2, message: 'validation failed');
        }
        // выполнить проверку наличия страны для редактирования
        $updatedCountry = $this->repository->selectByCode(code: $code);
        if ($updatedCountry === null) {
            // если страна не найдена - выбросить ошибку
            throw new CountryNotFoundException(notFoundCode: $code);
        }
        // проверить отсутствие дублирования кода при его обновлении
        if ($code != $country->isoAlpha2) {
            $duplicatedCodeCountry = $this->repository->selectByCode(code: $country->isoAlpha2);
            if ($duplicatedCodeCountry != null) {
                throw new DuplicatedCodeException(duplicatedCode: $country->isoAlpha2);
            }
        }
        // если все ок, то сделать update
        $this->repository->update(code: $code, country: $country);
    }

    // delete - удаление страны по коду
    // вход: код удаляемой страны
    // выход: -
    // исключения: InvalidCodeException, CountryNotFoundException
    public function delete(string $code) : void {
        // выполнить проверку корректности кода
        if (!$this->validateCode(code: $code)) {
            throw new InvalidCodeException(invalidCode: $code, message: 'validation failed');
        }
        // если валидация пройдена, то получить аэропорт по данному коду
        $country = $this->repository->selectByCode(code: $code);
        if ($country === null) {
            // если аэропорт не найден - выбросить ошибку
            throw new CountryNotFoundException(notFoundCode: $code);
        }
        $this->repository->delete(code: $code);
    }

    // validateCode - проверка корректности кода страны
    // вход: строка кода страны
    // выход: true если строка корректная, иначе false
    private function validateCode(string $code): bool {
        // ^[A-Z]{2}$
        return preg_match(pattern: '/^[A-Z]{2}$/', subject: $code);
    }
}