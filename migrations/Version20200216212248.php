<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200216212248 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE geo_cities ALTER name_canonical TYPE VARCHAR(120)');
        $this->addSql('ALTER TABLE geo_provinces ALTER name_canonical TYPE VARCHAR(120)');
        $this->addSql('ALTER TABLE geo_settlements ALTER name_canonical TYPE VARCHAR(120)');
        $this->addSql('ALTER TABLE geo_streets ALTER name_canonical TYPE VARCHAR(120)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE geo_provinces ALTER name_canonical TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE geo_settlements ALTER name_canonical TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE geo_cities ALTER name_canonical TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE geo_streets ALTER name_canonical TYPE VARCHAR(50)');
    }
}
