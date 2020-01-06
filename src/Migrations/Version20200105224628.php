<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200105224628 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE cooperatives_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cooperatives_members_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE cooperatives (id INT NOT NULL, ogrn BIGINT NOT NULL, inn BIGINT NOT NULL, kpp BIGINT NOT NULL, director TEXT NOT NULL, name VARCHAR(190) NOT NULL, title VARCHAR(190) DEFAULT NULL, description TEXT DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DCA860635E237E06 ON cooperatives (name)');
        $this->addSql('CREATE INDEX IDX_DCA86063E93323CB ON cooperatives (inn)');
        $this->addSql('CREATE INDEX IDX_DCA86063C4F9F519 ON cooperatives (kpp)');
        $this->addSql('CREATE INDEX IDX_DCA86063B89AB2C7 ON cooperatives (ogrn)');
        $this->addSql('CREATE INDEX IDX_DCA860638B8E8428 ON cooperatives (created_at)');
        $this->addSql('CREATE TABLE cooperatives_members (id INT NOT NULL, cooperative_id INT DEFAULT NULL, user_id UUID DEFAULT NULL, status SMALLINT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5D70D9E48D0C5D40 ON cooperatives_members (cooperative_id)');
        $this->addSql('CREATE INDEX IDX_5D70D9E4A76ED395 ON cooperatives_members (user_id)');
        $this->addSql('CREATE INDEX IDX_5D70D9E48B8E8428 ON cooperatives_members (created_at)');
        $this->addSql('COMMENT ON COLUMN cooperatives_members.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE cooperatives_members ADD CONSTRAINT FK_5D70D9E48D0C5D40 FOREIGN KEY (cooperative_id) REFERENCES cooperatives (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cooperatives_members ADD CONSTRAINT FK_5D70D9E4A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cooperatives_members DROP CONSTRAINT FK_5D70D9E48D0C5D40');
        $this->addSql('DROP SEQUENCE cooperatives_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cooperatives_members_id_seq CASCADE');
        $this->addSql('DROP TABLE cooperatives');
        $this->addSql('DROP TABLE cooperatives_members');
    }
}
