<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200210001743 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE geo_cities ADD timezone VARCHAR(4) DEFAULT NULL');
        $this->addSql('ALTER TABLE geo_cities ADD iso_code VARCHAR(4) DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_728C881562B6A45E ON geo_cities (iso_code)');
        $this->addSql('ALTER TABLE geo_regions ADD iso_code VARCHAR(4) DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_1571F4F162B6A45E ON geo_regions (iso_code)');
        $this->addSql('ALTER TABLE geo_settlements ADD timezone VARCHAR(4) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX IDX_1571F4F162B6A45E');
        $this->addSql('ALTER TABLE geo_regions DROP iso_code');
        $this->addSql('DROP INDEX IDX_728C881562B6A45E');
        $this->addSql('ALTER TABLE geo_cities DROP timezone');
        $this->addSql('ALTER TABLE geo_cities DROP iso_code');
        $this->addSql('ALTER TABLE geo_settlements DROP timezone');
    }
}
