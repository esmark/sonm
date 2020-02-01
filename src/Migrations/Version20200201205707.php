<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200201205707 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE products ADD tax_rate_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE products ALTER price_min SET NOT NULL');
        $this->addSql('ALTER TABLE products ALTER price_max SET NOT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AFDD13F95 FOREIGN KEY (tax_rate_id) REFERENCES tax_rates (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B3BA5A5AFDD13F95 ON products (tax_rate_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE products DROP CONSTRAINT FK_B3BA5A5AFDD13F95');
        $this->addSql('DROP INDEX IDX_B3BA5A5AFDD13F95');
        $this->addSql('ALTER TABLE products DROP tax_rate_id');
        $this->addSql('ALTER TABLE products ALTER price_min DROP NOT NULL');
        $this->addSql('ALTER TABLE products ALTER price_max DROP NOT NULL');
    }
}
