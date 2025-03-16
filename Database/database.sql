CREATE DATABASE faculty_leave_system;
USE faculty_leave_system;

-- Faculty Table
CREATE TABLE faculty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    total_leaves_taken INT DEFAULT 0,
    paternity_leaves_taken INT DEFAULT 0
);

-- Admin Table
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255)
);

-- Students Table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255)
);

-- Leave Requests Table
CREATE TABLE leave_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    faculty_id INT,
    leave_type VARCHAR(50),
    start_date DATE,
    end_date DATE,
    reason TEXT,
    status VARCHAR(20) DEFAULT 'Pending',
    FOREIGN KEY (faculty_id) REFERENCES faculty(id)
);

-- Faculty Ratings Table
CREATE TABLE faculty_ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    faculty_id INT,
    teaching_skills INT,
    ppt_usage INT,
    learning_effectiveness INT,
    overall_rating INT,
    comments TEXT,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (faculty_id) REFERENCES faculty(id)
);

-- Faculty Rating Summary Table
CREATE TABLE faculty_rating_summary (
    faculty_id INT PRIMARY KEY,
    average_teaching_skills FLOAT,
    average_ppt_usage FLOAT,
    average_learning_effectiveness FLOAT,
    overall_rating FLOAT,
    total_ratings INT,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id)
);
