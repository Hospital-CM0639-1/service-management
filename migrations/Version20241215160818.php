<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241215160818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE api_tokens (id SERIAL NOT NULL, user_id INT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, valid_from DATE DEFAULT NULL, valid_to DATE DEFAULT NULL, name VARCHAR(255) NOT NULL, token VARCHAR(512) NOT NULL, PRIMARY KEY(id));');
        $this->addSql('CREATE INDEX IDX_2CAD560EA76ED395 ON api_tokens (user_id);');
        $this->addSql('CREATE INDEX idx_token ON api_tokens (token);');
        $this->addSql('CREATE INDEX idx_valid_from ON api_tokens (valid_from);');
        $this->addSql('CREATE INDEX idx_valid_to ON api_tokens (valid_to);');
        $this->addSql('ALTER TABLE api_tokens ADD CONSTRAINT FK_2CAD560EA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;');

        $this->addSql("INSERT INTO user_types (id, name, code) VALUES (DEFAULT, 'Api'::varchar(255), 'api'::varchar(255));");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE api_tokens DROP CONSTRAINT FK_2CAD560EA76ED395');
        $this->addSql('DROP TABLE api_tokens');
    }
}
