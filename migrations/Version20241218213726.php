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
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE patients_patient_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE emergency_visits_visit_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE patient_vitals_vital_record_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE medical_procedures_procedure_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE hospital_beds_bed_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE patient_invoices_invoice_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE emergency_visit_staff (staff_role VARCHAR(255) NOT NULL, visit_id INT NOT NULL, staff_id INT NOT NULL, assigned_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY(visit_id, staff_id, staff_role))');
        $this->addSql('CREATE INDEX idx_emergency_visit_staff ON emergency_visit_staff (visit_id, staff_id)');
        $this->addSql('CREATE INDEX IDX_CFADB2C475FA0FF2 ON emergency_visit_staff (visit_id)');
        $this->addSql('CREATE INDEX IDX_CFADB2C4D4D57CD ON emergency_visit_staff (staff_id)');
        $this->addSql('CREATE TABLE patient_vitals (vital_record_id SERIAL NOT NULL, visit_id INT NOT NULL, recorded_by_staff_id INT NOT NULL, recorded_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP, body_temperature NUMERIC(4, 1) DEFAULT NULL, blood_pressure_systolic INT DEFAULT NULL, blood_pressure_diastolic INT DEFAULT NULL, heart_rate INT DEFAULT NULL, respiratory_rate INT DEFAULT NULL, oxygen_saturation NUMERIC(5, 2) DEFAULT NULL, additional_observations TEXT DEFAULT NULL, PRIMARY KEY(vital_record_id))');
        $this->addSql('CREATE INDEX IDX_5ED9214875FA0FF2 ON patient_vitals (visit_id)');
        $this->addSql('CREATE INDEX IDX_5ED9214838CA75F5 ON patient_vitals (recorded_by_staff_id)');
        $this->addSql('CREATE TABLE patient_invoices (invoice_id SERIAL NOT NULL, visit_id INT NOT NULL, created_by_staff_id INT NOT NULL, total_amount NUMERIC(10, 2) NOT NULL, invoice_timestamp TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP, payment_status VARCHAR(20) DEFAULT \'pending\', payment_received_amount NUMERIC(10, 2) DEFAULT \'0\', payment_received_timestamp TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, PRIMARY KEY(invoice_id))');
        $this->addSql('CREATE INDEX IDX_5DAA773075FA0FF2 ON patient_invoices (visit_id)');
        $this->addSql('CREATE INDEX IDX_5DAA7730D972133 ON patient_invoices (created_by_staff_id)');
        $this->addSql('CREATE TABLE patients (patient_id SERIAL NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, date_of_birth DATE NOT NULL, gender VARCHAR(50) DEFAULT NULL, contact_number VARCHAR(20) DEFAULT NULL, emergency_contact_name VARCHAR(200) DEFAULT NULL, emergency_contact_number VARCHAR(20) DEFAULT NULL, address TEXT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, insurance_provider VARCHAR(100) DEFAULT NULL, insurance_policy_number VARCHAR(100) DEFAULT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP, last_updated TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY(patient_id))');
        $this->addSql('CREATE INDEX idx_patient_name ON patients (last_name, first_name)');
        $this->addSql('CREATE TABLE medical_procedures (procedure_id SERIAL NOT NULL, visit_id INT NOT NULL, performed_by_staff_id INT NOT NULL, procedure_name VARCHAR(255) NOT NULL, procedure_timestamp TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP, description TEXT DEFAULT NULL, procedure_cost NUMERIC(10, 2) DEFAULT NULL, PRIMARY KEY(procedure_id))');
        $this->addSql('CREATE INDEX IDX_BDDBD78E75FA0FF2 ON medical_procedures (visit_id)');
        $this->addSql('CREATE INDEX IDX_BDDBD78E94A09A49 ON medical_procedures (performed_by_staff_id)');
        $this->addSql('CREATE TABLE hospital_beds (bed_id SERIAL NOT NULL, current_visit_id INT DEFAULT NULL, ward_section VARCHAR(50) NOT NULL, bed_number VARCHAR(20) NOT NULL, current_status VARCHAR(255) NOT NULL, last_cleaned_timestamp TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, PRIMARY KEY(bed_id))');
        $this->addSql('CREATE INDEX idx_bed_status ON hospital_beds (current_status)');
        $this->addSql('CREATE UNIQUE INDEX hospital_beds_ward_section_bed_number_key ON hospital_beds (ward_section, bed_number)');
        $this->addSql('CREATE INDEX IDX_B70BCD3BE88275D8 ON hospital_beds (current_visit_id)');
        $this->addSql('CREATE TABLE emergency_visits (visit_id SERIAL NOT NULL, priority_level VARCHAR(20) DEFAULT \'white\' NOT NULL, patient_id INT NOT NULL, admission_timestamp TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP, discharge_timestamp TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, current_status VARCHAR(255) NOT NULL, triage_notes TEXT DEFAULT NULL, PRIMARY KEY(visit_id))');
        $this->addSql('CREATE INDEX idx_visit_status ON emergency_visits (current_status)');
        $this->addSql('CREATE INDEX idx_visit_priority ON emergency_visits (priority_level)');
        $this->addSql('CREATE INDEX idx_patient_visits ON emergency_visits (patient_id)');
        $this->addSql('CREATE TABLE priority_levels (priority_code VARCHAR(20) NOT NULL, priority_name VARCHAR(50) NOT NULL, color_code VARCHAR(7) NOT NULL, description TEXT DEFAULT NULL, display_order INT NOT NULL, is_active BOOLEAN DEFAULT true, PRIMARY KEY(priority_code))');
        $this->addSql('ALTER TABLE emergency_visit_staff ADD CONSTRAINT emergency_visit_staff_visit_id_fkey FOREIGN KEY (visit_id) REFERENCES emergency_visits (visit_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE emergency_visit_staff ADD CONSTRAINT emergency_visit_staff_staff_id_fkey FOREIGN KEY (staff_id) REFERENCES staff (staff_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE patient_vitals ADD CONSTRAINT patient_vitals_visit_id_fkey FOREIGN KEY (visit_id) REFERENCES emergency_visits (visit_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE patient_vitals ADD CONSTRAINT patient_vitals_recorded_by_staff_id_fkey FOREIGN KEY (recorded_by_staff_id) REFERENCES staff (staff_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE patient_invoices ADD CONSTRAINT patient_invoices_visit_id_fkey FOREIGN KEY (visit_id) REFERENCES emergency_visits (visit_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE patient_invoices ADD CONSTRAINT patient_invoices_created_by_staff_id_fkey FOREIGN KEY (created_by_staff_id) REFERENCES staff (staff_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE medical_procedures ADD CONSTRAINT medical_procedures_visit_id_fkey FOREIGN KEY (visit_id) REFERENCES emergency_visits (visit_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE medical_procedures ADD CONSTRAINT medical_procedures_performed_by_staff_id_fkey FOREIGN KEY (performed_by_staff_id) REFERENCES staff (staff_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hospital_beds ADD CONSTRAINT hospital_beds_current_visit_id_fkey FOREIGN KEY (current_visit_id) REFERENCES emergency_visits (visit_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE emergency_visits ADD CONSTRAINT emergency_visits_priority_level_fkey FOREIGN KEY (priority_level) REFERENCES priority_levels (priority_code) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE emergency_visits ADD CONSTRAINT emergency_visits_patient_id_fkey FOREIGN KEY (patient_id) REFERENCES patients (patient_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE enable_disable_user_logs DROP CONSTRAINT FK_C48DE599B03A8386');
        $this->addSql('ALTER TABLE enable_disable_user_logs DROP CONSTRAINT FK_C48DE599A76ED395');
        $this->addSql('ALTER TABLE user_password_histories DROP CONSTRAINT FK_F8417D7AA76ED395');
        $this->addSql('DROP TABLE blacklisted_passwords');
        $this->addSql('DROP TABLE enable_disable_user_logs');
        $this->addSql('DROP TABLE user_password_histories');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9D4D57CD');
        $this->addSql('DROP INDEX IDX_1483A5E9D4D57CD');
        $this->addSql('DROP INDEX idx_active');
        $this->addSql('ALTER TABLE users DROP staff_id');
        $this->addSql('ALTER TABLE users DROP active');
        $this->addSql('CREATE SEQUENCE staff_staff_id_seq');
        $this->addSql('SELECT setval(\'staff_staff_id_seq\', (SELECT MAX(staff_id) FROM staff))');
        $this->addSql('ALTER TABLE staff ALTER staff_id SET DEFAULT nextval(\'staff_staff_id_seq\')');
        $this->addSql('ALTER TABLE staff ALTER is_active DROP NOT NULL');
        $this->addSql('ALTER INDEX uniq_426ef392e7927c74 RENAME TO staff_email_key');
    }
}
