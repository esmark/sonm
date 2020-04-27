<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200427123658 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE invites (id UUID NOT NULL, user_id UUID DEFAULT NULL, is_used BOOLEAN DEFAULT \'false\' NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_37E6A6CA76ED395 ON invites (user_id)');
        $this->addSql('CREATE INDEX IDX_37E6A6C8B8E8428 ON invites (created_at)');
        $this->addSql('CREATE INDEX IDX_37E6A6CECB44D39 ON invites (is_used)');
        $this->addSql('COMMENT ON COLUMN invites.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invites.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE invites ADD CONSTRAINT FK_37E6A6CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD invited_by_user_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD invite_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN users.invited_by_user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users.invite_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9EDB25FDD FOREIGN KEY (invited_by_user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9EA417747 FOREIGN KEY (invite_id) REFERENCES invites (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1483A5E9EDB25FDD ON users (invited_by_user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9EA417747 ON users (invite_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9EA417747');
        $this->addSql('DROP TABLE invites');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9EDB25FDD');
        $this->addSql('DROP INDEX IDX_1483A5E9EDB25FDD');
        $this->addSql('DROP INDEX UNIQ_1483A5E9EA417747');
        $this->addSql('ALTER TABLE users DROP invited_by_user_id');
        $this->addSql('ALTER TABLE users DROP invite_id');
    }
}
