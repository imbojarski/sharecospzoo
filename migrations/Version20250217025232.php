<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217025232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE monthly_payments (id SERIAL NOT NULL, monthly_rent_id INT DEFAULT NULL, payment_terms_id INT DEFAULT NULL, days_paid INT DEFAULT NULL, paid_from TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, paid_to TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, payment_amount DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN monthly_payments.paid_from IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN monthly_payments.paid_to IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE monthly_rent (id SERIAL NOT NULL, rental_contract_id INT NOT NULL, payment_term_id INT NOT NULL, payment_period INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE payments_term (id SERIAL NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, currency_symbol VARCHAR(255) NOT NULL, unit_price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN payments_term.start_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN payments_term.end_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE rental_contract (id SERIAL NOT NULL, customer_id INT NOT NULL, object_id INT NOT NULL, object_size INT NOT NULL, last_payment_term_id INT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE monthly_payments');
        $this->addSql('DROP TABLE monthly_rent');
        $this->addSql('DROP TABLE payments_term');
        $this->addSql('DROP TABLE rental_contract');
    }
}
