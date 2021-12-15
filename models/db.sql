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

CREATE TABLE Customer(
    id_customer SERIAL PRIMARY KEY,
    firstname_customer VARCHAR(100),
    lastname_customer VARCHAR(100),
    address_customer VARCHAR(255),
    type_age_customer BOOLEAN,
    gender_customer BOOLEAN,

);


-- ALTER TABLE FOR ADD COLUMN
ALTER TABLE Account 
    ADD id_session_account VARCHAR(255);

ALTER TABLE Contact 
    ADD id_country INTEGER;


-- ALTER TABLE FOR FOREIGN KEY
ALTER TABLE Orders
    ADD FOREIGN KEY (PersonID) REFERENCES Persons(PersonID);