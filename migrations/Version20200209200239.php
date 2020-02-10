<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200209200239 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE geo_regions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE geo_regions (id INT NOT NULL, aoguid VARCHAR(36) NOT NULL, aoid VARCHAR(36) NOT NULL, code VARCHAR(18) NOT NULL, okato VARCHAR(11) NOT NULL, formalname VARCHAR(120) NOT NULL, offname VARCHAR(120) NOT NULL, shortname VARCHAR(10) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1571F4F163238D6E ON geo_regions (aoguid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1571F4F11FB5B6C8 ON geo_regions (aoid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1571F4F177153098 ON geo_regions (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1571F4F1EE2C06AF ON geo_regions (okato)');
        $this->addSql('CREATE INDEX IDX_1571F4F18B8E8428 ON geo_regions (created_at)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE geo_regions_id_seq CASCADE');
        $this->addSql('DROP TABLE geo_regions');
    }
}
