<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200209222504 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE geo_cities ADD plaincode VARCHAR(15) NOT NULL');
        $this->addSql('ALTER TABLE geo_provinces ADD plaincode VARCHAR(15) NOT NULL');
        $this->addSql('DROP INDEX uniq_1571f4f177153098');
        $this->addSql('ALTER TABLE geo_regions ADD plaincode VARCHAR(15) NOT NULL');
        $this->addSql('ALTER TABLE geo_regions RENAME COLUMN code TO regioncode');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1571F4F14A33C28B ON geo_regions (regioncode)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_1571F4F14A33C28B');
        $this->addSql('ALTER TABLE geo_regions DROP plaincode');
        $this->addSql('ALTER TABLE geo_regions RENAME COLUMN regioncode TO code');
        $this->addSql('CREATE UNIQUE INDEX uniq_1571f4f177153098 ON geo_regions (code)');
        $this->addSql('ALTER TABLE geo_provinces DROP plaincode');
        $this->addSql('ALTER TABLE geo_cities DROP plaincode');
    }
}
