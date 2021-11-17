-- Create users table
CREATE TABLE `users`
(
    username VARCHAR(30)  NOT NULL
        PRIMARY KEY,
    password VARCHAR(255) NULL
);

-- Sample users data
INSERT INTO `users` (`username`, `password`)
VALUES ('admin', '$2y$10$btxU66SvdgCAnoVTyt7KLumhQ6kWI7ppBtS5rY5huXFJ7f5Gavtua');

-- Create patients table
CREATE TABLE `patients`
(
    id          INT AUTO_INCREMENT
        PRIMARY KEY,
    ic          VARCHAR(30)  NOT NULL,
    fullName    VARCHAR(50)  NOT NULL,
    gender      VARCHAR(30)  NOT NULL,
    address     VARCHAR(255) NOT NULL,
    dob         DATE         NOT NULL,
    email       VARCHAR(30)  NOT NULL,
    age         INT(30)      NOT NULL,
    phoneNumber VARCHAR(30)  NOT NULL
);

-- Sample patients data
INSERT INTO `patients` (`id`, `ic`, `fullName`, `gender`, `address`, `dob`, `email`, `age`, `phoneNumber`)
VALUES (3, '901234-56-1239', 'Daphne', 'Female', '108, jalan 8 / 149k, sri petaling', '1993-11-03',
        'kayjing.low@sd.taylors.edu.my', 28, '+60123456789'),
       (8, '901234-56-7892', 'Pug', 'Female', '108, jalan 8 / 149k, sri petaling', '1995-11-04',
        'kayjing.low@sd.taylors.edu.my', 26, '+60123456789'),
       (18, '901234-56-7893', 'Cheng', 'Male', 'JAy', '1997-05-12', 'kuiqiang@taylors.edu.my', 24, '+60198188048'),
       (19, '670512-10-5678', 'The Rock', 'Male', 'USA', '1967-05-12', 'the.rock@sd.taylors.edu.my', 54,
        '+60198188048'),
       (20, '901234-56-8888', 'Kate Low', 'Male', '108, jalan 8 / 149k, sri petaling', '1993-11-06',
        'kayjing.low@sd.taylors.edu.my', 28, '+60123456789'),
       (21, '901234-56-0101', 'Kate Low', 'Male', '108, jalan 8 / 149k, sri petaling', '1992-01-01',
        'kayjing.low@sd.taylors.edu.my', 29, '+60163878048'),
       (22, '901234-56-0099', 'smart', 'Female', 'taylors', '1990-12-05', 'kayjing.low@sd.taylors.edu.my', 30,
        '+60163878048'),
       (23, '901234-56-9999', 'Kate Low', 'Female', '108, jalan 8 / 149k, sri petaling', '1953-05-11',
        'kayjing.low@sd.taylors.edu.my', 68, '+60163878048'),
       (24, '901234-56-7894', 'Kate Low', 'Female', 'sri petaling', '1995-08-17', 'kayjing.low@sd.taylors.edu.my', 26,
        '+60163878048'),
       (25, '901234-56-7895', 'Kate Low', 'Female', 'sri petaling', '1995-08-17', 'kayjing.low@sd.taylors.edu.my', 26,
        '+60163878048');