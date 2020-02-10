<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200209210144 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE geo_provinces_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE geo_provinces (id INT NOT NULL, region_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, aoguid VARCHAR(36) NOT NULL, aoid VARCHAR(36) NOT NULL, areacode VARCHAR(3) NOT NULL, formalname VARCHAR(120) NOT NULL, ifnsfl VARCHAR(4) NOT NULL, ifnsul VARCHAR(4) NOT NULL, offname VARCHAR(120) NOT NULL, okato VARCHAR(11) NOT NULL, shortname VARCHAR(10) NOT NULL, regioncode VARCHAR(2) NOT NULL, terrifnsfl VARCHAR(4) NOT NULL, terrifnsul VARCHAR(4) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DA9E65E63238D6E ON geo_provinces (aoguid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DA9E65E1FB5B6C8 ON geo_provinces (aoid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DA9E65EA8AE91DE ON geo_provinces (areacode)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DA9E65E72A6CAB7 ON geo_provinces (ifnsfl)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DA9E65E13498B25 ON geo_provinces (ifnsul)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DA9E65EEE2C06AF ON geo_provinces (okato)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DA9E65E5C3AB584 ON geo_provinces (terrifnsfl)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DA9E65E3DD5F416 ON geo_provinces (terrifnsul)');
        $this->addSql('CREATE INDEX IDX_2DA9E65E98260155 ON geo_provinces (region_id)');
        $this->addSql('CREATE INDEX IDX_2DA9E65E8B8E8428 ON geo_provinces (created_at)');
        $this->addSql('CREATE INDEX IDX_2DA9E65E572A2CC2 ON geo_provinces (offname)');
        $this->addSql('CREATE INDEX IDX_2DA9E65E4A33C28B ON geo_provinces (regioncode)');
        $this->addSql('ALTER TABLE geo_provinces ADD CONSTRAINT FK_2DA9E65E98260155 FOREIGN KEY (region_id) REFERENCES geo_regions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE geo_regions ALTER code TYPE VARCHAR(2)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE geo_provinces_id_seq CASCADE');
        $this->addSql('DROP TABLE geo_provinces');
        $this->addSql('ALTER TABLE geo_regions ALTER code TYPE VARCHAR(18)');
    }
}
