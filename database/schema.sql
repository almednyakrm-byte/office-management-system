CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE دفاتر (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE موافقات (
  id INT AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE موارد (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE user_permissions (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  permission VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE دفاتر_موظف (
  id INT AUTO_INCREMENT,
  دفتر_id INT NOT NULL,
  موظف_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (دفتر_id) REFERENCES دفاتر(id),
  FOREIGN KEY (موظف_id) REFERENCES users(id)
);

CREATE TABLE موافقات_موظف (
  id INT AUTO_INCREMENT,
  موافقة_id INT NOT NULL,
  موظف_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (موافقة_id) REFERENCES موافقات(id),
  FOREIGN KEY (موظف_id) REFERENCES users(id)
);

CREATE TABLE موارد_موظف (
  id INT AUTO_INCREMENT,
  مورد_id INT NOT NULL,
  موظف_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (مورد_id) REFERENCES موارد(id),
  FOREIGN KEY (موظف_id) REFERENCES users(id)
);

INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO دفاتر (name, description) VALUES
('دفتر 1', 'وصف دفتر 1'),
('دفتر 2', 'وصف دفتر 2');

INSERT INTO موافقات (title, description) VALUES
('موافقة 1', 'وصف موافقة 1'),
('موافقة 2', 'وصف موافقة 2');

INSERT INTO موارد (name, description) VALUES
('مورد 1', 'وصف مورد 1'),
('مورد 2', 'وصف مورد 2');

INSERT INTO user_permissions (user_id, permission) VALUES
(1, 'الرئيسية'),
(1, 'قائمة الموظفين'),
(1, 'قائمة الموافقات'),
(1, 'قائمة الموارد');

INSERT INTO دفاتر_موظف (دفتر_id, موظف_id) VALUES
(1, 1),
(2, 1);

INSERT INTO موافقات_موظف (موافقة_id, موظف_id) VALUES
(1, 1),
(2, 1);

INSERT INTO موارد_موظف (مورد_id, موظف_id) VALUES
(1, 1),
(2, 1);