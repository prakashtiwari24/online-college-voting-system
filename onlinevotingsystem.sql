
CREATE DATABASE IF NOT EXISTS onlinevotingsystem;
USE onlinevotingsystem;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    contact_no VARCHAR(45),
    password TEXT,
    user_role VARCHAR(45)
);

CREATE TABLE elections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    election_topic VARCHAR(255),
    no_of_candidates INT,
    starting_date DATE,
    ending_date DATE,
    status VARCHAR(45),
    inserted_by VARCHAR(255),
    inserted_on DATE
);

CREATE TABLE candidate_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    election_id INT,
    candidate_name VARCHAR(255),
    candidate_details TEXT,
    candidate_photo TEXT,
    inserted_by VARCHAR(255),
    inserted_on DATE
);

CREATE TABLE votings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    election_id INT,
    voters_id INT,
    candidate_id INT,
    vote_date DATE,
    vote_time TIME
);

INSERT INTO users (username, contact_no, password, user_role) VALUES 
('Ankit(Admin)', '91234567890', SHA1('admin123'), 'Admin'),
('Vishal', '8888888885', SHA1('voter123'), 'Voter'),
('Bala', '9888888885', SHA1('voter123'), 'Voter');;

INSERT INTO elections (election_topic, no_of_candidates, starting_date, ending_date, status, inserted_by, inserted_on) VALUES 
('College President Election', 2, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 DAY), 'Active', 'Admin User', CURDATE());

INSERT INTO candidate_details (election_id, candidate_name, candidate_details, candidate_photo, inserted_by, inserted_on) VALUES
(1, 'Shalini', 'Senior Year Student', 'assets/images/candidate_photo/shalini.jpeg', 'Admin User', CURDATE()),
(1, 'Rahul', 'Cultural Secretary', 'assets/images/candidate_photo/rahul.jpeg', 'Admin User', CURDATE());
