<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200216203516 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX uniq_c969656d64082763');
        $this->addSql('DROP INDEX idx_c969656d9aeacc13');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C969656D9AEACC1364082763 ON geo_abbreviations (level, shortname)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_C969656D9AEACC1364082763');
        $this->addSql('CREATE UNIQUE INDEX uniq_c969656d64082763 ON geo_abbreviations (shortname)');
        $this->addSql('CREATE INDEX idx_c969656d9aeacc13 ON geo_abbreviations (level)');
    }
}
