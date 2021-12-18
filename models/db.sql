-- Syntax SQL 
-- For Database TicketQ

-- Setting : composer require ticketq
use ticketq;

-- CREATE TABLE 
CREATE TABLE Account(
    id_account SERIAL PRIMARY KEY,
    name_account VARCHAR(100),
    username_account VARCHAR(55),
    email_account VARCHAR(55),
    password_account VARCHAR(255)
);

ALTER TABLE Account 
    ADD id_session_account VARCHAR(255);

CREATE TABLE Country (
    id_country SERIAL PRIMARY KEY,
    iso_country CHAR(2),
    name_capital_country VARCHAR(100),
    name_country VARCHAR(100),
    iso3_country CHAR(3),
    numcode_country VARCHAR(6),
    phonecode_country VARCHAR(6)
);

CREATE TABLE Contact (
    id_contact SERIAL PRIMARY KEY,
    email_contact VARCHAR(55),
    telp_contact VARCHAR(12)
);

ALTER TABLE Contact 
    ADD id_country INTEGER;

ALTER TABLE Contact
    ADD FOREIGN KEY (id_country) REFERENCES Country(id_country);

CREATE TABLE Customer(
    id_customer SERIAL PRIMARY KEY,
    firstname_customer VARCHAR(100),
    lastname_customer VARCHAR(100),
    address_customer VARCHAR(255),
    type_age_customer BOOLEAN,
    gender_customer CHAR(1),
    nationality_customer VARCHAR(32),
    id_account INTEGER,
    id_contact INTEGER,

    FOREIGN KEY (id_account) REFERENCES Account(id_account),
    FOREIGN KEY (id_contact) REFERENCES Contact(id_contact)
);

CREATE TABLE Plane (
    id_plane VARCHAR(4) PRIMARY KEY,
    capacity_plane INTEGER,
    company_plane VARCHAR(55),
    type_plane VARCHAR(55)
);

CREATE TABLE Routes (
    id_route VARCHAR(8) PRIMARY KEY,
    start_city_route VARCHAR(55),
    destination_city_route VARCHAR(55),
    start_airport_route VARCHAR(55), 
    destination_destination_route VARCHAR(55)
);

