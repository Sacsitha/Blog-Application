-- InkFlow application schema
-- Run this file against the configured blog_db database.

CREATE TABLE IF NOT EXISTS user (
  user_id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(50) NOT NULL,
  password varchar(255) NOT NULL,
  full_name varchar(100),
  email varchar(100) NOT NULL,
  bio varchar(300),
  avatar_url varchar(255),
  created_at timestamp DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id),
  UNIQUE KEY username (username),
  UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS blog (
  blog_id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  title varchar(120) NOT NULL,
  subtitle varchar(180),
  body longtext NOT NULL,
  cover_image_url varchar(255),
  created_at timestamp DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (blog_id),
  CONSTRAINT blog_user_fk FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS interest (
  interest_id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL,
  PRIMARY KEY (interest_id),
  UNIQUE KEY name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS user_interest (
  user_interest_id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  interest_id int(11) NOT NULL,
  PRIMARY KEY (user_interest_id),
  UNIQUE KEY user_interest_unique (user_id, interest_id),
  CONSTRAINT user_interest_user_fk FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE,
  CONSTRAINT user_interest_interest_fk FOREIGN KEY (interest_id) REFERENCES interest(interest_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO interest (name) VALUES
  ('Technology'),
  ('Business'),
  ('Health & Wellness'),
  ('Entertainment'),
  ('Sports'),
  ('Travel');
