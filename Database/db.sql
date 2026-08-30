CREATE DATABASE projeto_avaliacao;
USE projeto_avaliacao;

CREATE TABLE user (
	id_user BIGINT NOT NULL AUTO_INCREMENT,
  name VARCHAR(150),
  email VARCHAR(100),
  password VARCHAR(100),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  update_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  ativo BOOLEAN,
  PRIMARY KEY (id_user)
);

CREATE TABLE service(
	id_service BIGINT NOT NULL AUTO_INCREMENT,
  description VARCHAR(45),
  price DECIMAL(11,3),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  update_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  finished_at DATETIME,
  commission_user DECIMAL(11,3),
	user_id_user BIGINT,
  PRIMARY KEY (id_service),
  CONSTRAINT fk_service_user
  FOREIGN KEY (user_id_user) REFERENCES user(id_user)
);