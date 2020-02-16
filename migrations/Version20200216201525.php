<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200216201525 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE geo_abbreviations_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE geo_abbreviations (id INT NOT NULL, code SMALLINT NOT NULL, level SMALLINT NOT NULL, fullname VARCHAR(50) NOT NULL, shortname VARCHAR(10) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C969656D77153098 ON geo_abbreviations (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C969656D9AEACC13 ON geo_abbreviations (level)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C969656D91657DAE ON geo_abbreviations (fullname)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C969656D64082763 ON geo_abbreviations (shortname)');
        $this->addSql('ALTER TABLE geo_regions ADD abbreviation_id INT NOT NULL');
        $this->addSql('ALTER TABLE geo_regions ADD CONSTRAINT FK_1571F4F1BF69284D FOREIGN KEY (abbreviation_id) REFERENCES geo_abbreviations (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1571F4F1BF69284D ON geo_regions (abbreviation_id)');
        $this->addSql('ALTER TABLE geo_settlements ADD abbreviation_id INT NOT NULL');
        $this->addSql('ALTER TABLE geo_settlements ADD CONSTRAINT FK_DC6E2D2BF69284D FOREIGN KEY (abbreviation_id) REFERENCES geo_abbreviations (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_DC6E2D2BF69284D ON geo_settlements (abbreviation_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE geo_regions DROP CONSTRAINT FK_1571F4F1BF69284D');
        $this->addSql('ALTER TABLE geo_settlements DROP CONSTRAINT FK_DC6E2D2BF69284D');
        $this->addSql('DROP SEQUENCE geo_abbreviations_id_seq CASCADE');
        $this->addSql('DROP TABLE geo_abbreviations');
        $this->addSql('DROP INDEX IDX_DC6E2D2BF69284D');
        $this->addSql('ALTER TABLE geo_settlements DROP abbreviation_id');
        $this->addSql('DROP INDEX IDX_1571F4F1BF69284D');
        $this->addSql('ALTER TABLE geo_regions DROP abbreviation_id');
    }
}
