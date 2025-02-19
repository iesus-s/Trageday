CREATE TABLE IF NOT EXISTS tragedies (
    id INT PRIMARY KEY,
    month VARCHAR(255) NOT NULL,
    day INT NOT NULL,
    year INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    summary TEXT NOT NULL, 
    map VARCHAR(255) NOT NULL
); 

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    trageday VARCHAR(255) NOT NULL
); 

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    submitted_at DATETIME NOT NULL
); 

SET GLOBAL time_zone = 'America/Los_Angeles';
