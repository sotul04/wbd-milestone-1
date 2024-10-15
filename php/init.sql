DROP TABLE IF EXISTS lamaran;
DROP TABLE IF EXISTS attachments_lowongan;
DROP TABLE IF EXISTS lowongan;
DROP TABLE IF EXISTS company_details;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) CHECK (role IN ('jobseeker', 'company')),
    nama VARCHAR(255) NOT NULL
);

CREATE TABLE company_details (
    user_id INT PRIMARY KEY,  
    lokasi VARCHAR(255),
    about TEXT,
    CONSTRAINT fk_company_user
        FOREIGN KEY (user_id) 
        REFERENCES users(user_id)
        ON DELETE CASCADE  
        ON UPDATE CASCADE  
);

CREATE TABLE lowongan (
    lowongan_id SERIAL PRIMARY KEY,
    company_id INT,
    posisi VARCHAR(255),
    deskripsi TEXT,
    jenis_pekerjaan VARCHAR(255),
    jenis_lokasi VARCHAR(255),
    is_open BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_lowongan_company
        FOREIGN KEY (company_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE  
        ON UPDATE CASCADE  
);

CREATE TABLE attachments_lowongan (
    attachment_id SERIAL PRIMARY KEY,
    lowongan_id INT,
    file_path VARCHAR(255),
    CONSTRAINT fk_attachment_lowongan
        FOREIGN KEY (lowongan_id)
        REFERENCES lowongan(lowongan_id)
        ON DELETE CASCADE  
        ON UPDATE CASCADE
);

CREATE TABLE lamaran (
    lamaran_id SERIAL PRIMARY KEY,
    user_id INT,  
    lowongan_id INT,  
    cv_path VARCHAR(255),
    video_path VARCHAR(255),
    status VARCHAR(50) CHECK (status IN ('accepted', 'rejected', 'waiting')),
    status_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_lamaran_user
        FOREIGN KEY (user_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    CONSTRAINT fk_lamaran_lowongan
        FOREIGN KEY (lowongan_id)
        REFERENCES lowongan(lowongan_id)
        ON DELETE CASCADE 
        ON UPDATE CASCADE
);

-- Seed users table
INSERT INTO users (email, password, role, nama) VALUES
    ('jobseeker1@example.com', 'password1', 'jobseeker', 'Job Seeker 1'),
    ('company1@example.com', 'password1', 'company', 'Company 1'),
    ('jobseeker2@example.com', 'password2', 'jobseeker', 'Job Seeker 2'),
    ('company2@example.com', 'password2', 'company', 'Company 2');

-- Seed company_details table
INSERT INTO company_details (user_id, lokasi, about) VALUES
    (2, 'Jakarta', 'Company 1 is a tech startup based in Jakarta.'),
    (4, 'Bandung', 'Company 2 is a well-established company in Bandung.');

-- Seed lowongan table
INSERT INTO lowongan (company_id, posisi, deskripsi, jenis_pekerjaan, jenis_lokasi) VALUES
    (2, 'Software Engineer', 'We are looking for a software engineer.', 'Full-time', 'Remote'),
    (2, 'UI/UX Designer', 'We are looking for a creative UI/UX designer.', 'Full-time', 'On-site'),
    (4, 'Data Analyst', 'We are looking for a data analyst to join our team.', 'Contract', 'Remote');

-- Seed attachments_lowongan table
INSERT INTO attachments_lowongan (lowongan_id, file_path) VALUES
    (1, '/files/software_engineer_description.pdf'),
    (2, '/files/ui_ux_designer_description.pdf'),
    (3, '/files/data_analyst_description.pdf');

-- Seed lamaran table
INSERT INTO lamaran (user_id, lowongan_id, cv_path, video_path, status, status_reason) VALUES
    (1, 1, '/files/jobseeker1_cv.pdf', '/files/jobseeker1_video.mp4', 'waiting', ''),
    (3, 2, '/files/jobseeker2_cv.pdf', '/files/jobseeker2_video.mp4', 'waiting', '');