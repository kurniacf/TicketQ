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
    start_city_route VARCHAR(100),
    destination_city_route VARCHAR(100),
    start_airport_route VARCHAR(100), 
    destination_airport_route VARCHAR(100)
);

CREATE TABLE Schedule (
    id_schedule VARCHAR(16) PRIMARY KEY,
    id_plane VARCHAR(4),
    id_route VARCHAR(8),
    departure_schedule TIMESTAMP,
    arrival_schedule TIMESTAMP,
    available_seat_schedule INTEGER,
    normal_price_schedule INTEGER,

    FOREIGN KEY (id_plane) REFERENCES Plane(id_plane),
    FOREIGN KEY (id_route) REFERENCES Routes(id_route)
);

CREATE TABLE Discount (
    id_discount VARCHAR(4) PRIMARY KEY,
    title_discount VARCHAR(32),
    number_discount INTEGER,
    description_discount TEXT
);

CREATE TABLE Additional_Fee (
    id_fee VARCHAR(4) PRIMARY KEY,
    title_fee VARCHAR(32),
    number_fee INTEGER,
    description_fee TEXT
);

CREATE TABLE Cek_Transactions(
    id_customer INTEGER PRIMARY KEY,
    id_transactions INTEGER PRIMARY KEY
);

CREATE TABLE Transactions(
    id_transactions serial PRIMARY KEY,
    id_customer INTEGER,
    id_schedule VARCHAR(16),
    booking_date TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    price_transactions INTEGER,
    seat_number INTEGER,
    customer_adult INTEGER,
    customer_child INTEGER,

    FOREIGN KEY (id_customer) REFERENCES Customer(id_customer),
    FOREIGN KEY (id_schedule) REFERENCES Schedule(id_schedule)
);

CREATE TABLE Ticketing(
    id_ticketing serial PRIMARY KEY,
    id_transactions INTEGER,
    id_discount VARCHAR(4),
    id_fee VARCHAR(4),
    total_price_transactions INTEGER,
    total_customer_adult INTEGER,
    total_customer_child INTEGER,

    FOREIGN KEY (id_transactions) REFERENCES Transactions(id_transactions),
    FOREIGN KEY (id_discount) REFERENCES Discount(id_discount),
    FOREIGN KEY (id_fee) REFERENCES Additional_Fee(id_fee)
);

CREATE TABLE Ticketing(
    id_ticketing serial PRIMARY KEY,
    id_schedule VARCHAR(16),
    id_account INTEGER,
    id_discount VARCHAR(4),
    id_fee VARCHAR(4),
    total_price_transactions INTEGER,
    total_customer_adult INTEGER,
    total_customer_child INTEGER,

    FOREIGN KEY (id_schedule) REFERENCES Schedule(id_schedule),
    FOREIGN KEY (id_account) REFERENCES Account(id_account),
    FOREIGN KEY (id_discount) REFERENCES Discount(id_discount),
    FOREIGN KEY (id_fee) REFERENCES Additional_Fee(id_fee)
);