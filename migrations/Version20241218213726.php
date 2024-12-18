<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241218213726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('CREATE TABLE enable_disable_user_logs (id SERIAL NOT NULL, created_by_id INT DEFAULT NULL, user_id INT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, action VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C48DE599B03A8386 ON enable_disable_user_logs (created_by_id)');
        $this->addSql('CREATE INDEX IDX_C48DE599A76ED395 ON enable_disable_user_logs (user_id)');
        $this->addSql('ALTER TABLE enable_disable_user_logs ADD CONSTRAINT FK_C48DE599B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE enable_disable_user_logs ADD CONSTRAINT FK_C48DE599A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('ALTER TABLE users ADD staff_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD active BOOLEAN DEFAULT true NOT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9D4D57CD FOREIGN KEY (staff_id) REFERENCES staff (staff_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1483A5E9D4D57CD ON users (staff_id)');
        $this->addSql('CREATE INDEX idx_active ON users (active)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
