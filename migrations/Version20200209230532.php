<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200209230532 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE geo_cities ADD latitude NUMERIC(14, 11) DEFAULT NULL');
        $this->addSql('ALTER TABLE geo_cities ADD longitude NUMERIC(14, 11) DEFAULT NULL');
        $this->addSql('ALTER TABLE geo_settlements ADD latitude NUMERIC(14, 11) DEFAULT NULL');
        $this->addSql('ALTER TABLE geo_settlements ADD longitude NUMERIC(14, 11) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE geo_settlements DROP latitude');
        $this->addSql('ALTER TABLE geo_settlements DROP longitude');
        $this->addSql('ALTER TABLE geo_cities DROP latitude');
        $this->addSql('ALTER TABLE geo_cities DROP longitude');
    }
}
