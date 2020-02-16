<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200216203240 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX uniq_c969656d9aeacc13');
        $this->addSql('CREATE INDEX IDX_C969656D8B8E8428 ON geo_abbreviations (created_at)');
        $this->addSql('CREATE INDEX IDX_C969656D9AEACC13 ON geo_abbreviations (level)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX IDX_C969656D8B8E8428');
        $this->addSql('DROP INDEX IDX_C969656D9AEACC13');
        $this->addSql('CREATE UNIQUE INDEX uniq_c969656d9aeacc13 ON geo_abbreviations (level)');
    }
}
