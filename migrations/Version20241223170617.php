<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241223170617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE user_password_histories ADD created_by_id INT DEFAULT NULL;');
        $this->addSql('ALTER TABLE user_password_histories ADD CONSTRAINT FK_F8417D7AB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->addSql('CREATE INDEX IDX_F8417D7AB03A8386 ON user_password_histories (created_by_id);');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_password_histories DROP CONSTRAINT FK_F8417D7AB03A8386');
        $this->addSql('DROP INDEX IDX_F8417D7AB03A8386');
        $this->addSql('ALTER TABLE user_password_histories DROP created_by_id');
    }
}
