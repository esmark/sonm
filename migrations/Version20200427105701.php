<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200427105701 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX idx_fc59c1a4572a2cc2');
        $this->addSql('DROP INDEX idx_fc59c1a42269d851');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FC59C1A42269D851 ON geo_countries (engname)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FC59C1A4D322CED6 ON geo_countries (name_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FC59C1A4572A2CC2 ON geo_countries (offname)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FC59C1A4B19E32DF ON geo_countries (iso_code_alpha2)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FC59C1A4C6990249 ON geo_countries (iso_code_alpha3)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FC59C1A4EFC0BFFA ON geo_countries (iso_code_numeric)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_FC59C1A42269D851');
        $this->addSql('DROP INDEX UNIQ_FC59C1A4D322CED6');
        $this->addSql('DROP INDEX UNIQ_FC59C1A4572A2CC2');
        $this->addSql('DROP INDEX UNIQ_FC59C1A4B19E32DF');
        $this->addSql('DROP INDEX UNIQ_FC59C1A4C6990249');
        $this->addSql('DROP INDEX UNIQ_FC59C1A4EFC0BFFA');
        $this->addSql('CREATE INDEX idx_fc59c1a4572a2cc2 ON geo_countries (offname)');
        $this->addSql('CREATE INDEX idx_fc59c1a42269d851 ON geo_countries (engname)');
    }
}
