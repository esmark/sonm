<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200114055234 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE pick_up_locations_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE pick_up_locations (id INT NOT NULL, user_id UUID NOT NULL, latitude NUMERIC(14, 11) DEFAULT NULL, longitude NUMERIC(14, 11) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, title VARCHAR(190) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_enabled BOOLEAN DEFAULT \'true\', PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C06E24BA76ED395 ON pick_up_locations (user_id)');
        $this->addSql('CREATE INDEX IDX_C06E24B8B8E8428 ON pick_up_locations (created_at)');
        $this->addSql('CREATE INDEX IDX_C06E24B2B36786B ON pick_up_locations (title)');
        $this->addSql('COMMENT ON COLUMN pick_up_locations.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE pick_up_locations ADD CONSTRAINT FK_C06E24BA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE pick_up_locations_id_seq CASCADE');
        $this->addSql('DROP TABLE pick_up_locations');
    }
}
