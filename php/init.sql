-- Drop tables if they already exist
DROP TABLE IF EXISTS lamaran;
DROP TABLE IF EXISTS attachments_lowongan;
DROP TABLE IF EXISTS lowongan;
DROP TABLE IF EXISTS company_details;
DROP TABLE IF EXISTS users;

-- Drop existing ENUM types if they exist
DROP TYPE IF EXISTS enum_jenis_lokasi;
DROP TYPE IF EXISTS enum_jenis_pekerjaan;
DROP TYPE IF EXISTS enum_role;
DROP TYPE IF EXISTS enum_status;

-- Create ENUM types
CREATE TYPE enum_role AS ENUM ('jobseeker', 'company');
CREATE TYPE enum_jenis_lokasi AS ENUM ('on-site', 'hybrid', 'remote');
CREATE TYPE enum_jenis_pekerjaan AS ENUM ('Full-time', 'Part-time', 'Internship');
CREATE TYPE enum_status AS ENUM ('accepted', 'rejected', 'waiting');

-- Create users table
CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role enum_role NOT NULL,  -- Changed to ENUM
    nama VARCHAR(255) NOT NULL
);

-- Create company_details table
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

-- Create lowongan table
CREATE TABLE lowongan (
    lowongan_id SERIAL PRIMARY KEY,
    company_id INT,
    posisi VARCHAR(255),
    deskripsi TEXT,
    jenis_pekerjaan enum_jenis_pekerjaan NOT NULL,  -- Changed to ENUM
    jenis_lokasi enum_jenis_lokasi NOT NULL,  -- Changed to ENUM
    is_open BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_lowongan_company
        FOREIGN KEY (company_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE  
        ON UPDATE CASCADE  
);

-- Create attachments_lowongan table
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

-- Create lamaran table
CREATE TABLE lamaran (
    lamaran_id SERIAL PRIMARY KEY,
    user_id INT,  
    lowongan_id INT,  
    cv_path VARCHAR(255),
    video_path VARCHAR(255),
    status enum_status NOT NULL,  -- Changed to ENUM
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
