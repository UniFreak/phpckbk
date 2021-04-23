create table users (
    email varchar(255) not null,
    created_on datetime not null,
    verify_string varchar(16) not null,
    verified tinyint unsigned
);