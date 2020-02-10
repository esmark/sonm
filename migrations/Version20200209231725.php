<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200209231725 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE geo_streets_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE geo_streets (id INT NOT NULL, city_id INT DEFAULT NULL, settlement_id INT DEFAULT NULL, province_id INT DEFAULT NULL, region_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, aoguid VARCHAR(36) NOT NULL, aoid VARCHAR(36) NOT NULL, areacode VARCHAR(3) NOT NULL, citycode VARCHAR(3) NOT NULL, formalname VARCHAR(120) NOT NULL, offname VARCHAR(120) NOT NULL, okato VARCHAR(11) NOT NULL, oktmo VARCHAR(11) NOT NULL, shortname VARCHAR(10) NOT NULL, regioncode VARCHAR(2) NOT NULL, plaincode VARCHAR(15) NOT NULL, placecode VARCHAR(3) NOT NULL, streetcode VARCHAR(4) NOT NULL, latitude NUMERIC(14, 11) DEFAULT NULL, longitude NUMERIC(14, 11) DEFAULT NULL, ifnsfl VARCHAR(4) NOT NULL, ifnsul VARCHAR(4) NOT NULL, terrifnsfl VARCHAR(4) NOT NULL, terrifnsul VARCHAR(4) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_24E0F63C63238D6E ON geo_streets (aoguid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_24E0F63C1FB5B6C8 ON geo_streets (aoid)');
        $this->addSql('CREATE INDEX IDX_24E0F63C8BAC62AF ON geo_streets (city_id)');
        $this->addSql('CREATE INDEX IDX_24E0F63CC2B9C425 ON geo_streets (settlement_id)');
        $this->addSql('CREATE INDEX IDX_24E0F63CE946114A ON geo_streets (province_id)');
        $this->addSql('CREATE INDEX IDX_24E0F63C98260155 ON geo_streets (region_id)');
        $this->addSql('CREATE INDEX IDX_24E0F63C8B8E8428 ON geo_streets (created_at)');
        $this->addSql('CREATE INDEX IDX_24E0F63C572A2CC2 ON geo_streets (offname)');
        $this->addSql('CREATE INDEX IDX_24E0F63CA8AE91DE ON geo_streets (areacode)');
        $this->addSql('CREATE INDEX IDX_24E0F63C4A33C28B ON geo_streets (regioncode)');
        $this->addSql('CREATE INDEX IDX_24E0F63C35CC4F93 ON geo_streets (placecode)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_24E0F63C667A06F9 ON geo_streets (streetcode)');
        $this->addSql('ALTER TABLE geo_streets ADD CONSTRAINT FK_24E0F63C8BAC62AF FOREIGN KEY (city_id) REFERENCES geo_cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE geo_streets ADD CONSTRAINT FK_24E0F63CC2B9C425 FOREIGN KEY (settlement_id) REFERENCES geo_settlements (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE geo_streets ADD CONSTRAINT FK_24E0F63CE946114A FOREIGN KEY (province_id) REFERENCES geo_provinces (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE geo_streets ADD CONSTRAINT FK_24E0F63C98260155 FOREIGN KEY (region_id) REFERENCES geo_regions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE geo_streets_id_seq CASCADE');
        $this->addSql('DROP TABLE geo_streets');
    }
}
