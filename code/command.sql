CREATE TABLE professor(
    professor_ssn VARCHAR(11) PRIMARY KEY,
    full_name VARCHAR(255),
    street_address VARCHAR(255),
    city VARCHAR(255),
    state VARCHAR(255),
    zip_code VARCHAR(10),
    sex VARCHAR(10),
    title VARCHAR (255),
    area_code VARCHAR(3),
    seven_digit_number VARCHAR(8),
    salary DECIMAL(8, 2),
    CONSTRAINT chk_sex CHECK (sex IN ('Male', 'Female', 'Other'))
);

CREATE TABLE department (
    department_id INT PRIMARY KEY AUTO_INCREMENT,
    chairperson_ssn VARCHAR(11),
    full_name VARCHAR(255),
    office_location VARCHAR(255),
    telephone_number VARCHAR(14),
    FOREIGN KEY(chairperson_ssn) REFERENCES professor(professor_ssn) ON DELETE SET NULL
);

CREATE TABLE course (
    course_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    textbook VARCHAR(255),
    units TINYINT DEFAULT 3,
    department_id INT,
    FOREIGN KEY(department_id) REFERENCES department(department_id),
    CONSTRAINT chk_units_range CHECK (units BETWEEN 1 AND 5)
);

CREATE TABLE prerequisite_courses(
    course_id INT,
    prerequisite_course INT,
    PRIMARY KEY(course_id, prerequisite_course),
    FOREIGN KEY(course_id) REFERENCES course(course_id) ON DELETE CASCADE,
    FOREIGN KEY (prerequisite_course) REFERENCES course(course_id) ON DELETE CASCADE
);

CREATE TABLE student_record(
    cwid INT(9) PRIMARY KEY,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    street_address VARCHAR(255),
    telephone_number VARCHAR(14),
    department_id INT,
    FOREIGN KEY(department_id) REFERENCES department(department_id),
    CONSTRAINT chk_telephone_number CHECK (telephone_number REGEXP '^[0-9]{3}-[0-9]{3}-[0-9]{4}$')
);

CREATE TABLE college_degrees(
    professor_ssn VARCHAR(11),
    college_degree VARCHAR(255),
    PRIMARY KEY(professor_ssn, college_degree),
    FOREIGN KEY(professor_ssn) REFERENCES professor(professor_ssn)
);

CREATE TABLE section(
    professor_ssn VARCHAR(11),
    course_id INT,
    section_number INT,
    beginning_time TIME,
    ending_time TIME,
    classroom VARCHAR(255),
    number_of_seats INT(3),
    PRIMARY KEY(professor_ssn, course_id, section_number),
    FOREIGN KEY(professor_ssn) REFERENCES professor(professor_ssn),
    FOREIGN KEY(course_id) REFERENCES course(course_id)
);

CREATE TABLE meeting_days(
    professor_ssn VARCHAR(11),
    course_id INT,
    section_number INT,
    meeting_day VARCHAR(10),
    PRIMARY KEY(professor_ssn, course_id, section_number, meeting_day),
    FOREIGN KEY(professor_ssn, course_id, section_number) REFERENCES section(professor_ssn, course_id, section_number)
);

CREATE TABLE minors(
    department_id INT,
    cwid INT(9),
    PRIMARY KEY(department_id, cwid),
    FOREIGN KEY(department_id) REFERENCES department(department_id),
    FOREIGN KEY(cwid) REFERENCES student_record(cwid)
);

CREATE TABLE enrollment_records(
    cwid INT(9),
    professor_ssn VARCHAR(11),
    course_id INT,
    section_number INT,
    grade VARCHAR(2),
    PRIMARY KEY(cwid, professor_ssn, course_id, section_number),
    FOREIGN KEY(cwid) REFERENCES student_record(cwid),
    FOREIGN KEY(professor_ssn, course_id, section_number) REFERENCES section(professor_ssn, course_id, section_number)
);