<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200209224238 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE geo_settlements_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE geo_settlements (id INT NOT NULL, city_id INT DEFAULT NULL, province_id INT DEFAULT NULL, region_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, aoguid VARCHAR(36) NOT NULL, aoid VARCHAR(36) NOT NULL, areacode VARCHAR(3) NOT NULL, citycode VARCHAR(3) NOT NULL, formalname VARCHAR(120) NOT NULL, offname VARCHAR(120) NOT NULL, okato VARCHAR(11) NOT NULL, oktmo VARCHAR(11) NOT NULL, shortname VARCHAR(10) NOT NULL, regioncode VARCHAR(2) NOT NULL, plaincode VARCHAR(15) NOT NULL, placecode VARCHAR(3) NOT NULL, ifnsfl VARCHAR(4) NOT NULL, ifnsul VARCHAR(4) NOT NULL, terrifnsfl VARCHAR(4) NOT NULL, terrifnsul VARCHAR(4) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DC6E2D263238D6E ON geo_settlements (aoguid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DC6E2D21FB5B6C8 ON geo_settlements (aoid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DC6E2D2EE2C06AF ON geo_settlements (okato)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DC6E2D26FC1CE2C ON geo_settlements (oktmo)');
        $this->addSql('CREATE INDEX IDX_DC6E2D28BAC62AF ON geo_settlements (city_id)');
        $this->addSql('CREATE INDEX IDX_DC6E2D2E946114A ON geo_settlements (province_id)');
        $this->addSql('CREATE INDEX IDX_DC6E2D298260155 ON geo_settlements (region_id)');
        $this->addSql('CREATE INDEX IDX_DC6E2D28B8E8428 ON geo_settlements (created_at)');
        $this->addSql('CREATE INDEX IDX_DC6E2D2572A2CC2 ON geo_settlements (offname)');
        $this->addSql('CREATE INDEX IDX_DC6E2D2A8AE91DE ON geo_settlements (areacode)');
        $this->addSql('CREATE INDEX IDX_DC6E2D24A33C28B ON geo_settlements (regioncode)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DC6E2D235CC4F93 ON geo_settlements (placecode)');
        $this->addSql('ALTER TABLE geo_settlements ADD CONSTRAINT FK_DC6E2D28BAC62AF FOREIGN KEY (city_id) REFERENCES geo_cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE geo_settlements ADD CONSTRAINT FK_DC6E2D2E946114A FOREIGN KEY (province_id) REFERENCES geo_provinces (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE geo_settlements ADD CONSTRAINT FK_DC6E2D298260155 FOREIGN KEY (region_id) REFERENCES geo_regions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE geo_settlements_id_seq CASCADE');
        $this->addSql('DROP TABLE geo_settlements');
    }
}
