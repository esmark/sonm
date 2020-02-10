<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200210003759 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX uniq_728c88156fc1ce2c');
        $this->addSql('ALTER TABLE geo_cities ADD engname VARCHAR(120) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_728C88156FC1CE2C4A33C28B ON geo_cities (oktmo, regioncode)');
        $this->addSql('ALTER TABLE geo_provinces ADD engname VARCHAR(120) DEFAULT NULL');
        $this->addSql('ALTER TABLE geo_regions ADD engname VARCHAR(120) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE geo_provinces DROP engname');
        $this->addSql('ALTER TABLE geo_regions DROP engname');
        $this->addSql('DROP INDEX UNIQ_728C88156FC1CE2C4A33C28B');
        $this->addSql('ALTER TABLE geo_cities DROP engname');
        $this->addSql('CREATE UNIQUE INDEX uniq_728c88156fc1ce2c ON geo_cities (oktmo)');
    }
}
