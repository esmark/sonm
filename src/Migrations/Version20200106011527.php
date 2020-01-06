<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200106011527 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX idx_3f6a7b508cde5729');
        $this->addSql('ALTER TABLE cooperatives_history DROP comment');
        $this->addSql('ALTER TABLE cooperatives_history RENAME COLUMN type TO action');
        $this->addSql('CREATE INDEX IDX_3F6A7B5047CC8C92 ON cooperatives_history (action)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX IDX_3F6A7B5047CC8C92');
        $this->addSql('ALTER TABLE cooperatives_history ADD comment TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE cooperatives_history RENAME COLUMN action TO type');
        $this->addSql('CREATE INDEX idx_3f6a7b508cde5729 ON cooperatives_history (type)');
    }
}
