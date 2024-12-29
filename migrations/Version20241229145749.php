<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241229145749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users ADD patient_id INT DEFAULT NULL;');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E96B899279 FOREIGN KEY (patient_id) REFERENCES patients (patient_id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->addSql('CREATE INDEX IDX_1483A5E96B899279 ON users (patient_id);');
        $this->addSql("INSERT INTO public.user_types (id, name, code) VALUES (DEFAULT, 'Patient'::varchar(255), 'patient'::varchar(255));");
        $this->addSql('DROP INDEX uq_email;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E96B899279');
        $this->addSql('DROP INDEX IDX_1483A5E96B899279');
        $this->addSql('ALTER TABLE users DROP patient_id');
    }
}
