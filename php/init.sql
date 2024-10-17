-- Enable the uuid-ossp extension if not already enabled
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

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
    user_id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role enum_role NOT NULL,
    nama VARCHAR(255) NOT NULL
);

-- Create company_details table
CREATE TABLE company_details (
    user_id UUID PRIMARY KEY,
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
    lowongan_id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    company_id UUID,
    posisi VARCHAR(255),
    deskripsi TEXT,
    jenis_pekerjaan enum_jenis_pekerjaan NOT NULL,
    jenis_lokasi enum_jenis_lokasi NOT NULL,
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
    attachment_id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    lowongan_id UUID,
    file_path VARCHAR(255),
    CONSTRAINT fk_attachment_lowongan
        FOREIGN KEY (lowongan_id)
        REFERENCES lowongan(lowongan_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Create lamaran table
CREATE TABLE lamaran (
    lamaran_id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id UUID,
    lowongan_id UUID,
    cv_path VARCHAR(255),
    video_path VARCHAR(255),
    status enum_status NOT NULL,
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