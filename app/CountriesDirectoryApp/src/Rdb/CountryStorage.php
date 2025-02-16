<?php 

namespace App\Rdb;

use mysqli;
use RuntimeException;
use Exception;

use App\Model\Country;
use App\Model\CountryRepository;

// CountryStorage - имплементация хранилища стран, работающая с БД
class CountryStorage implements CountryRepository {

    public function __construct() {
        // при создании проверить доступность БД
        $this->pingDb();
    }
    
    // pingDb - проверить доступность БД
    private function pingDb() : void {
        // открыть и закрыть соединение с БД
        $connection = $this->openDbConnection();
        $connection->close();
    }

    // openDbConnection - открыть соединение с БД
    private function openDbConnection(): mysqli  {
        // зададим параметры подключения к БД        
        $host = '172.21.0.2';
        $port = 3306;
        $user = 'root';
        $password = 'root';
        $database = 'countries_db';
        // создать объект подключения через драйвер
        $connection = new mysqli(
            hostname: $host,
            port: $port, 
            username: $user, 
            password: $password, 
            database: $database, 
        );
        // открыть соединение с БД
        if ($connection->connect_errno) {
            throw new RuntimeException(message: "Failed to connect to MySQL: ".$connection->connect_error);
        }
        // если все ок - вернуть соединение с БД
        return $connection;
    }

    public function selectAll(): array {
        try {
            // подключение к БД
            $connection = $this->openDbConnection();
            // строка запроса
            $queryStr = '
                SELECT short_name_f, full_name_f, iso_alpha2_f, iso_alpha3_f, iso_numeric_f, population_f, area_f
                FROM country_t;';
            // выполнить запрос
            $rows = $connection->query(query: $queryStr);
            // считать резкльтатзапроса
            $countries = [];
            while ($row = $rows->fetch_array()) {
                $country = new Country(
                    shortName: $row[0],
                    fullName: $row[1],
                    isoAlpha2: $row[2],
                    isoAlpha3: $row[3],
                    isoNumeric: $row[4],
                    population: intval(value: $row[5]),
                    area: intval(value: $row[6]),
                );
                array_push($countries, $country);
            }
            // вернуть результат
            return $countries;
        } finally {
            // закрыть соединение с БД
            if (isset($connection)) {
                $connection->close();
            }
        }
    }

    public function selectByCode(string $code): ?Country {
        try {
            $connection = $this->openDbConnection();
            $queryStr = 'SELECT short_name_f, full_name_f, iso_alpha2_f, iso_alpha3_f, iso_numeric_f, population_f, area_f 
                FROM country_t
                WHERE iso_alpha2_f = ?';
            // подготовить запрос
            $query = $connection->prepare(query: $queryStr);
            $query->bind_param('s', $code);
            // выполнить запрос
            $query->execute();
            $rows = $query->get_result();
            // считать результаты запроса
            while ($row = $rows->fetch_array()) {
                // если есть результат - вернем его
                return new Country(
                    shortName: $row[0],
                    fullName: $row[1],
                    isoAlpha2: $row[2],
                    isoAlpha3: $row[3],
                    isoNumeric: $row[4],
                    population: intval(value: $row[5]),
                    area: intval(value: $row[6]),
                );
            }
            // иначе вернуть null
            return null;
        } finally {
            // закрыть соединение с БД
            if (isset($connection)) {
                $connection->close();
            }
        }
    }

    public function save(Country $country): void {
        try {
            // создать подключение к БД
            $connection = $this->openDbConnection();
            // подготовить запрос INSERT
            $queryStr = 'INSERT INTO country_t (short_name_f, full_name_f, iso_alpha2_f, iso_alpha3_f, iso_numeric_f, population_f, area_f)
                        VALUES (?, ?, ?, ?, ?, ?, ?);';
            // подготовить запрос
            $query = $connection->prepare(query: $queryStr);            
            $query->bind_param(
                'sssssii', 
                $country->shortName, 
                $country->fullName,
                $country->isoAlpha2, 
                $country->isoAlpha3, 
                $country->isoNumeric,
                $country->population,
                $country->area,
            );
            // выполнить запрос
            if (!$query->execute()) {
                throw new Exception(message: 'insert execute failed');
            }
        } finally {
            // в конце в любом случае закрыть соединение с БД если оно было открыто
            if (isset($connection)) {
                $connection->close();
            }
        }
    }

    public function delete(string $code):void {
        try {
            // создать подключение к БД
            $connection = $this->openDbConnection();
            // подготовить запрос INSERT
            $queryStr = 'DELETE FROM country_t WHERE iso_alpha2_f = ?';
            // подготовить запрос
            $query = $connection->prepare(query: $queryStr);
            $query->bind_param('s', $code);
            // выполнить запрос
            if (!$query->execute()) {
                throw new Exception(message: 'delete execute failed');
            }
        } finally {
            // в конце в любом случае закрыть соединение с БД если оно было открыто
            if (isset($connection)) {
                $connection->close();
            }
        }
    }

    function update(string $code, Country $country) : void {
        try {
            // создать подключение к БД
            $connection = $this->openDbConnection();
            // подготовить запрос INSERT
            $queryStr = 'UPDATE country_t SET 
                    short_name_f = ?, 
                    full_name_f = ?,
                    iso_alpha2_f = ?, 
                    iso_alpha3_f = ?, 
                    iso_numeric_f = ?,
                    population_f = ?,
                    area_f = ?
                WHERE iso_alpha2_f = ?';
            // подготовить запрос
            $query = $connection->prepare(query: $queryStr);            
            $query->bind_param(
                'sssssii', 
                $country->shortName, 
                $country->fullName,
                $country->isoAlpha2, 
                $country->isoAlpha3, 
                $country->isoNumeric,
                $country->population,
                $country->area,
            );
            // выполнить запрос
            if (!$query->execute()) {
                throw new Exception(message: 'update execute failed');
            }
        } finally {
            // в конце в любом случае закрыть соединение с БД если оно было открыто
            if (isset($connection)) {
                $connection->close();
            }
        }
    }
}