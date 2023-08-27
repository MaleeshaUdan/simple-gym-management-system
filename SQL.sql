CREATE TABLE user (
    username VARCHAR(30) PRIMARY KEY,
    name VARCHAR(30),
    password VARCHAR(255)
);

CREATE TABLE info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30),
    fullname VARCHAR(255),
    email VARCHAR(255),
    phonenumber VARCHAR(20),
    DOB VARCHAR(10),
    gender VARCHAR(10),
    address VARCHAR(255),
    emerphonenum VARCHAR(20),
    fitnessGoal VARCHAR(255),
    fitnessLevel VARCHAR(255),
    medicalHistory TEXT
);

ALTER TABLE info
ADD FOREIGN KEY (username) REFERENCES user(username);


CREATE TABLE BMI (
    username VARCHAR(255) ,
    height FLOAT ,
    weight FLOAT 
);

ALTER TABLE bmi
ADD FOREIGN KEY (username) REFERENCES user(username);

ALTER TABLE BMI
ADD bmi_value FLOAT(5, 2);


DELIMITER //

CREATE TRIGGER calculate_bmi
BEFORE INSERT ON BMI
FOR EACH ROW
BEGIN
    SET NEW.bmi_value = CASE
        WHEN NEW.height > 0 AND NEW.weight > 0 THEN NEW.weight / ((NEW.height / 100) * (NEW.height / 100))
        ELSE 0
    END;
END;
//

CREATE TRIGGER update_bmi
BEFORE UPDATE ON BMI
FOR EACH ROW
BEGIN
    IF NEW.height = 0 OR NEW.weight = 0 THEN
        SET NEW.bmi_value = 0;
    ELSE
        SET NEW.bmi_value = NEW.weight / ((NEW.height / 100) * (NEW.height / 100));
    END IF;
END;
//

DELIMITER ;





SELECT TRIGGER_NAME
FROM information_schema.TRIGGERS
WHERE TRIGGER_SCHEMA = 'gymdb';

DROP TRIGGER IF EXISTS calculate_bmi;
DROP TRIGGER IF EXISTS update_bmi;





