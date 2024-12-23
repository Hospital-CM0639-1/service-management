<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241210213145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE management_service_request_logs (id SERIAL NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, created_at_date DATE NOT NULL, ended_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, path_info VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, token TEXT DEFAULT NULL, content TEXT DEFAULT NULL, query_params TEXT DEFAULT NULL, body_params TEXT DEFAULT NULL, headers TEXT DEFAULT NULL, response TEXT DEFAULT NULL, response_status_code INT DEFAULT NULL, response_headers TEXT DEFAULT NULL, PRIMARY KEY(id));');
        $this->addSql('CREATE INDEX idx_username ON management_service_request_logs (username);');
        $this->addSql('CREATE INDEX idx_url ON management_service_request_logs (url);');
        $this->addSql('CREATE INDEX idx_path_info ON management_service_request_logs (path_info);');
        $this->addSql('CREATE INDEX idx_created_at_date ON management_service_request_logs (created_at_date);');
        $this->addSql('CREATE INDEX idx_response_status_code ON management_service_request_logs (response_status_code);');
        $this->addSql('CREATE TABLE user_types (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id));');
        $this->addSql('CREATE UNIQUE INDEX uq_code ON user_types (code);');
        $this->addSql('CREATE TABLE users (id SERIAL NOT NULL, type_id INT NOT NULL, created_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, last_login_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, first_login_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, password_changed_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, password_request_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, last_token TEXT DEFAULT NULL, PRIMARY KEY(id));');
        $this->addSql('CREATE INDEX IDX_1483A5E9C54C8C93 ON users (type_id);');
        $this->addSql('CREATE INDEX IDX_1483A5E9B03A8386 ON users (created_by_id);');
        $this->addSql('CREATE INDEX idx_email ON users (email);');
        $this->addSql('CREATE UNIQUE INDEX uq_username ON users (username);');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9C54C8C93 FOREIGN KEY (type_id) REFERENCES user_types (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE;');

        $this->addSql("INSERT INTO user_types (id, name, code)
            VALUES (DEFAULT, 'Admin', 'admin')");

                    $this->addSql("INSERT INTO public.users (id, type_id, created_by_id, name, surname, email, username, password, last_login_at,
                                      first_login_at, created_at, password_changed_at, password_request_at, last_token)
            VALUES (DEFAULT, 1, null, 'Admin', 'admin', 'admin@example.com', 'admin',
                    '$2a$12$/wSge4VXeGK9ydxT/C7vPe8tOWNuKpXrxfIDpOQpIa5fjuEMLjEsi', null, null, '2024-12-10 21:36:52 +00:00', '2024-12-10 21:36:52 +00:00',
                    null, null);");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9C54C8C93');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9B03A8386');
        $this->addSql('DROP TABLE management_service_request_logs');
        $this->addSql('DROP TABLE user_types');
        $this->addSql('DROP TABLE users');
    }
}
