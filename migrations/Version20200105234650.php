<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200105234650 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE cooperatives_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE cooperatives_history (id INT NOT NULL, cooperative_id INT DEFAULT NULL, user_id UUID DEFAULT NULL, new_value TEXT DEFAULT NULL, old_value TEXT DEFAULT NULL, type SMALLINT NOT NULL, comment TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3F6A7B508D0C5D40 ON cooperatives_history (cooperative_id)');
        $this->addSql('CREATE INDEX IDX_3F6A7B50A76ED395 ON cooperatives_history (user_id)');
        $this->addSql('CREATE INDEX IDX_3F6A7B508B8E8428 ON cooperatives_history (created_at)');
        $this->addSql('CREATE INDEX IDX_3F6A7B508CDE5729 ON cooperatives_history (type)');
        $this->addSql('COMMENT ON COLUMN cooperatives_history.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN cooperatives_history.new_value IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN cooperatives_history.old_value IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE cooperatives_history ADD CONSTRAINT FK_3F6A7B508D0C5D40 FOREIGN KEY (cooperative_id) REFERENCES cooperatives (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cooperatives_history ADD CONSTRAINT FK_3F6A7B50A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cooperatives ALTER director TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE cooperatives ALTER director DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE cooperatives_history_id_seq CASCADE');
        $this->addSql('DROP TABLE cooperatives_history');
        $this->addSql('ALTER TABLE cooperatives ALTER director TYPE TEXT');
        $this->addSql('ALTER TABLE cooperatives ALTER director DROP DEFAULT');
    }
}
