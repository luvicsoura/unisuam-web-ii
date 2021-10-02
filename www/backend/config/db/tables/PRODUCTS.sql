CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug varchar(100) UNIQUE,
    name VARCHAR(100),
    description TEXT,
    quantity MEDIUMINT,
    image VARCHAR(500)
) ENGINE = InnoDB DEFAULT CHARSET = latin1