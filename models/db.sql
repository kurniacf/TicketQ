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

CREATE TABLE Region (
    id_region SERIAL PRIMARY KEY,
    name_region VARCHAR(55)
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

