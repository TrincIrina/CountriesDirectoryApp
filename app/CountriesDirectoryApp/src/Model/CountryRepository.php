<?php 

namespace App\Model;

// CountryRepository - интерфейс хранилища стран
interface CountryRepository {
    
    // selectAll - получение всех стран
    function selectAll(): array;

    // selectByCode - получить страну по коду
    function selectByCode(string $code): ?Country;

    // save - сохранение страны в БД
    function save(Country $country): void;

    // delete - удаление страны по коду
    function delete(string $code) : void;

    // update - обновление данных страны по коду
    function update(string $code, Country $country) : void;
}
