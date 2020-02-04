<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200120092100 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE programs_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE programs (id INT NOT NULL, user_id UUID NOT NULL, title VARCHAR(190) DEFAULT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_enabled BOOLEAN DEFAULT \'true\', PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F1496545A76ED395 ON programs (user_id)');
        $this->addSql('CREATE INDEX IDX_F14965458B8E8428 ON programs (created_at)');
        $this->addSql('CREATE INDEX IDX_F14965452B36786B ON programs (title)');
        $this->addSql('COMMENT ON COLUMN programs.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE programs ADD CONSTRAINT FK_F1496545A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE programs_id_seq CASCADE');
        $this->addSql('DROP TABLE programs');
    }
}
