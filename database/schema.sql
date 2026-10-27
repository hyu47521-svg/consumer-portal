-- Consumer Protection Portal - database schema
-- Import this file in phpMyAdmin (cPanel) or run it with the mysql command.

CREATE TABLE IF NOT EXISTS users (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name     VARCHAR(120) NOT NULL,
  email         VARCHAR(190) NOT NULL UNIQUE,
  phone         VARCHAR(30)  NULL,
  password_hash VARCHAR(255) NOT NULL,
  role          ENUM('consumer','staff','admin') NOT NULL DEFAULT 'consumer',
  is_active     TINYINT(1) NOT NULL DEFAULT 1,
  created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS complaints (
  id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  reference_no     VARCHAR(20) NOT NULL UNIQUE,
  user_id          INT UNSIGNED NULL,
  category         ENUM('scam_fraud','billing_dispute','unfair_practice','product_service','other') NOT NULL,
  business_name    VARCHAR(190) NOT NULL,
  business_contact VARCHAR(190) NULL,
  amount_disputed  DECIMAL(12,2) NULL,
  incident_date    DATE NULL,
  description      TEXT NOT NULL,
  contact_name     VARCHAR(120) NOT NULL,
  contact_email    VARCHAR(190) NOT NULL,
  status           ENUM('Submitted','Under Review','In Progress','Resolved','Closed') NOT NULL DEFAULT 'Submitted',
  created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_complaints_user (user_id),
  INDEX idx_complaints_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS complaint_updates (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  complaint_id   INT UNSIGNED NOT NULL,
  author_user_id INT UNSIGNED NULL,
  update_type    ENUM('status_change','message','note') NOT NULL,
  new_status     ENUM('Submitted','Under Review','In Progress','Resolved','Closed') NULL,
  message        TEXT NULL,
  is_internal    TINYINT(1) NOT NULL DEFAULT 0,
  created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (complaint_id) REFERENCES complaints(id) ON DELETE CASCADE,
  FOREIGN KEY (author_user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_updates_complaint (complaint_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS attachments (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  complaint_id  INT UNSIGNED NOT NULL,
  original_name VARCHAR(255) NOT NULL,
  stored_name   VARCHAR(255) NOT NULL UNIQUE,
  mime_type     VARCHAR(100) NOT NULL,
  file_size     INT UNSIGNED NOT NULL,
  uploaded_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (complaint_id) REFERENCES complaints(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS content_pages (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug         VARCHAR(120) NOT NULL UNIQUE,
  title        VARCHAR(190) NOT NULL,
  body         MEDIUMTEXT NOT NULL,
  is_published TINYINT(1) NOT NULL DEFAULT 1,
  updated_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS login_attempts (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email      VARCHAR(190) NOT NULL,
  ip_address VARCHAR(45) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_attempts (email, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
