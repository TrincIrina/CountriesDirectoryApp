-- создание БД
DROP DATABASE IF EXISTS countries_db;
CREATE DATABASE countries_db;
-- переключение на данную БД
USE countries_db;
-- создание таблицы аэропортов
CREATE TABLE country_t (
	id INT NOT NULL AUTO_INCREMENT,
    short_name_f NVARCHAR(200) NOT NULL,
    full_name_f NVARCHAR(200),
    iso_alpha2_f CHAR(2) NOT NULL,
    iso_alpha3_f CHAR(3) NOT NULL,
    iso_numeric_f CHAR(3) NOT NULL,
    population_f INT NOT NULL,
    area_f INT NOT NULL,
    --
    PRIMARY KEY(id),
    UNIQUE(short_name_f),
    UNIQUE(iso_alpha2_f),
    UNIQUE(iso_alpha3_f),
    UNIQUE(iso_numeric_f)
);
