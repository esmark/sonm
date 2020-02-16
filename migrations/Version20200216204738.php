<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200216204738 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE geo_cities ADD abbreviation_id INT NOT NULL');
        $this->addSql('ALTER TABLE geo_cities ADD CONSTRAINT FK_728C8815BF69284D FOREIGN KEY (abbreviation_id) REFERENCES geo_abbreviations (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_728C8815BF69284D ON geo_cities (abbreviation_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE geo_cities DROP CONSTRAINT FK_728C8815BF69284D');
        $this->addSql('DROP INDEX IDX_728C8815BF69284D');
        $this->addSql('ALTER TABLE geo_cities DROP abbreviation_id');
    }
}
