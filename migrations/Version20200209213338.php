<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200209213338 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE geo_cities_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE geo_cities (id INT NOT NULL, province_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, aoguid VARCHAR(36) NOT NULL, aoid VARCHAR(36) NOT NULL, areacode VARCHAR(3) NOT NULL, formalname VARCHAR(120) NOT NULL, offname VARCHAR(120) NOT NULL, okato VARCHAR(11) NOT NULL, shortname VARCHAR(10) NOT NULL, regioncode VARCHAR(2) NOT NULL, ifnsfl VARCHAR(4) NOT NULL, ifnsul VARCHAR(4) NOT NULL, terrifnsfl VARCHAR(4) NOT NULL, terrifnsul VARCHAR(4) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_728C881563238D6E ON geo_cities (aoguid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_728C88151FB5B6C8 ON geo_cities (aoid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_728C8815A8AE91DE ON geo_cities (areacode)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_728C8815EE2C06AF ON geo_cities (okato)');
        $this->addSql('CREATE INDEX IDX_728C8815E946114A ON geo_cities (province_id)');
        $this->addSql('CREATE INDEX IDX_728C88158B8E8428 ON geo_cities (created_at)');
        $this->addSql('CREATE INDEX IDX_728C8815572A2CC2 ON geo_cities (offname)');
        $this->addSql('CREATE INDEX IDX_728C88154A33C28B ON geo_cities (regioncode)');
        $this->addSql('ALTER TABLE geo_cities ADD CONSTRAINT FK_728C8815E946114A FOREIGN KEY (province_id) REFERENCES geo_provinces (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE geo_cities_id_seq CASCADE');
        $this->addSql('DROP TABLE geo_cities');
    }
}
