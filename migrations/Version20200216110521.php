<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200216110521 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE geo_cities ADD postalcode VARCHAR(6) DEFAULT NULL');
        $this->addSql('ALTER TABLE geo_cities ALTER centstatus SET DEFAULT 0');
        $this->addSql('ALTER TABLE geo_settlements ADD postalcode VARCHAR(6) DEFAULT NULL');
        $this->addSql('ALTER TABLE geo_settlements ALTER centstatus SET DEFAULT 0');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE geo_cities DROP postalcode');
        $this->addSql('ALTER TABLE geo_cities ALTER centstatus DROP DEFAULT');
        $this->addSql('ALTER TABLE geo_settlements DROP postalcode');
        $this->addSql('ALTER TABLE geo_settlements ALTER centstatus DROP DEFAULT');
    }
}
