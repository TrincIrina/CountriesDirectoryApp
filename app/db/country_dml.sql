-- заполнение БД
USE countries_db;
-- удалить данные
TRUNCATE TABLE country_t;
-- добавить данные
INSERT INTO country_t (
	short_name_f, 
    full_name_f, 
    iso_alpha2_f, 
    iso_alpha3_f, 
    iso_numeric_f,
    population_f,
    area_f
) VALUES 
	('Россия', 'Российская Федерация', 'RU', 'RUS', '643', 146150789, 17125191),
    ('Китай', 'Китайская Народная Республика', 'CN', 'CHN', '156', 1411750000, 9598962),
    ('Гваделупа', NULL, 'GP', 'GLP', '312', 383569, 1628);
-- получим данные
SELECT * FROM country_t;
