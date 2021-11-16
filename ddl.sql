create schema pms collate utf8mb4_general_ci;

create table users
(
    username varchar(30)  not null
        primary key,
    password varchar(255) null
);

create table patients
(
    id          int auto_increment
        primary key,
    ic          varchar(30)  not null,
    fullName    varchar(50)  not null,
    gender      varchar(30)  not null,
    address     varchar(255) not null,
    dob         date         not null,
    email       varchar(30)  not null,
    age         int(30)      not null,
    phoneNumber varchar(30)  not null
);