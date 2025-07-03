CREATE TABLE `phpcatcom_testmodule` (
    id INT(11) NOT NULL AUTO_INCREMENT,
    code VARCHAR(3) NOT NULL,
    date DATETIME NOT NULL,
    course FLOAT NOT NULL,
    PRIMARY KEY (id),
    INDEX idx_code_date (code, date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
