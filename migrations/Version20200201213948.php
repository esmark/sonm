<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200201213948 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX uniq_dca860635e237e06');
        $this->addSql('ALTER TABLE cooperatives RENAME COLUMN name TO slug');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DCA86063989D9B62 ON cooperatives (slug)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_DCA86063989D9B62');
        $this->addSql('ALTER TABLE cooperatives RENAME COLUMN slug TO name');
        $this->addSql('CREATE UNIQUE INDEX uniq_dca860635e237e06 ON cooperatives (name)');
    }
}
