USE aziz_cours;
CREATE TABLE cours (
id INT PRIMARY KEY AUTO_INCREMENT,
title VARCHAR(255),
levele ENUM("Débutant", "Intermédiaire", "Avancé"),
create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
--------------------------------------------------------
--------------------------------------------------------
CREATE TABLE sections (
section_id INT AUTO_INCREMENT PRIMARY KEY,
cours_id INT,
section_title VARCHAR(255) NOT NULL,
section_content TEXT,
position INT NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP    
);
---------------------------------------------------------
---------------------------------------------------------
ALTER TABLE cours 
RENAME COLUMN id
TO cours_id;
---------------------------------------------------------
---------------------------------------------------------
ALTER TABLE sections
ADD CONSTRAINT fk_sections_cours_id
FOREIGN KEY (cours_id)
REFERENCES cours(cours_id);
---------------------------------------------------------
---------------------------------------------------------
ALTER TABLE cours
RENAME COLUMN create_at TO created_at
---------------------------------------------------------
---------------------------------------------------------
ALTER TABLE cours
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
---------------------------------------------------------
---------------------------------------------------------
ALTER TABLE cours
MODIFY COLUMN levele ENUM('Débutant', 'Intermédiaire', 'Avancé')
DEFAULT 'Débutant';
--------------------------------------------------------
--------------------------------------------------------
ALTER TABLE cours
ADD CONSTRAINT unique_title UNIQUE (title)
--------------------------------------------------------
--------------------------------------------------------
ALTER TABLE sections
DROP FOREIGN key fk_sections_cours_id
--------------------------------------------------------
--------------------------------------------------------
ALTER TABLE sections
ADD CONSTRAINT fk_sections_cours_id
FOREIGN KEY (cours_id)
REFERENCES cours(cours_id) 
ON DELETE CASCADE;
--------------------------------------------------------
--------------------------------------------------------
ALTER TABLE cours
ADD COLUMN image VARCHAR(255)
