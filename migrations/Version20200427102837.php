<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200427102837 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE geo_countries_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE geo_countries (id INT NOT NULL, iso_code_alpha2 VARCHAR(2) NOT NULL, iso_code_alpha3 VARCHAR(3) NOT NULL, iso_code_numeric SMALLINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, code VARCHAR(17) NOT NULL, name_canonical VARCHAR(120) NOT NULL, offname VARCHAR(120) NOT NULL, engname VARCHAR(120) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FC59C1A48B8E8428 ON geo_countries (created_at)');
        $this->addSql('CREATE INDEX IDX_FC59C1A42269D851 ON geo_countries (engname)');
        $this->addSql('CREATE INDEX IDX_FC59C1A4572A2CC2 ON geo_countries (offname)');
        $this->addSql('CREATE INDEX IDX_728C88152269D851 ON geo_cities (engname)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE geo_countries_id_seq CASCADE');
        $this->addSql('DROP TABLE geo_countries');
        $this->addSql('DROP INDEX IDX_728C88152269D851');
    }
}
