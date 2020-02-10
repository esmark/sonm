<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200209211344 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE geo_regions ADD ifnsfl VARCHAR(4) NOT NULL');
        $this->addSql('ALTER TABLE geo_regions ADD ifnsul VARCHAR(4) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1571F4F172A6CAB7 ON geo_regions (ifnsfl)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1571F4F113498B25 ON geo_regions (ifnsul)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_1571F4F172A6CAB7');
        $this->addSql('DROP INDEX UNIQ_1571F4F113498B25');
        $this->addSql('ALTER TABLE geo_regions DROP ifnsfl');
        $this->addSql('ALTER TABLE geo_regions DROP ifnsul');
    }
}
