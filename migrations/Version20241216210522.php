<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241216210522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blacklisted_passwords (id SERIAL NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id));');
        $this->addSql('CREATE TABLE user_password_histories (id SERIAL NOT NULL, user_id INT NOT NULL, password VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id));');
        $this->addSql('CREATE INDEX IDX_F8417D7AA76ED395 ON user_password_histories (user_id);');
        $this->addSql('ALTER TABLE user_password_histories ADD CONSTRAINT FK_F8417D7AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_password_histories DROP CONSTRAINT FK_F8417D7AA76ED395');
        $this->addSql('DROP TABLE blacklisted_passwords');
        $this->addSql('DROP TABLE user_password_histories');
    }
}
