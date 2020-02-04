<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200105015040 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE users_groups_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_oauths_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE users (id UUID NOT NULL, username VARCHAR(40) NOT NULL, username_canonical VARCHAR(40) NOT NULL, email VARCHAR(100) NOT NULL, email_canonical VARCHAR(100) NOT NULL, password VARCHAR(190) NOT NULL, roles TEXT NOT NULL, firstname VARCHAR(30) NOT NULL, lastname VARCHAR(30) NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, latitude NUMERIC(14, 11) DEFAULT NULL, longitude NUMERIC(14, 11) DEFAULT NULL, telegram_user_id INT DEFAULT NULL, telegram_username VARCHAR(100) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_enabled BOOLEAN DEFAULT \'true\', PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E992FC23A8 ON users (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9A0D96FBF ON users (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9FC28B263 ON users (telegram_user_id)');
        $this->addSql('CREATE INDEX IDX_1483A5E98B8E8428 ON users (created_at)');
        $this->addSql('CREATE INDEX IDX_1483A5E983A00E68 ON users (firstname)');
        $this->addSql('CREATE INDEX IDX_1483A5E93124B5B6 ON users (lastname)');
        $this->addSql('CREATE INDEX IDX_1483A5E9C843FD0B ON users (last_login)');
        $this->addSql('CREATE INDEX IDX_1483A5E946C53D4C ON users (is_enabled)');
        $this->addSql('COMMENT ON COLUMN users.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users.roles IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE users_groups_relations (user_id UUID NOT NULL, user_group_id INT NOT NULL, PRIMARY KEY(user_id, user_group_id))');
        $this->addSql('CREATE INDEX IDX_12DA6D62A76ED395 ON users_groups_relations (user_id)');
        $this->addSql('CREATE INDEX IDX_12DA6D621ED93D47 ON users_groups_relations (user_group_id)');
        $this->addSql('COMMENT ON COLUMN users_groups_relations.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE users_groups (id INT NOT NULL, roles TEXT NOT NULL, name VARCHAR(255) NOT NULL, position SMALLINT DEFAULT 0, title VARCHAR(190) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FF8AB7E08B8E8428 ON users_groups (created_at)');
        $this->addSql('CREATE INDEX IDX_FF8AB7E0462CE4F5 ON users_groups (position)');
        $this->addSql('CREATE INDEX IDX_FF8AB7E02B36786B ON users_groups (title)');
        $this->addSql('COMMENT ON COLUMN users_groups.roles IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE users_oauths (id INT NOT NULL, user_id UUID DEFAULT NULL, access_token VARCHAR(100) NOT NULL, refresh_token VARCHAR(100) DEFAULT NULL, identifier BIGINT NOT NULL, provider VARCHAR(20) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88E49D4AB6A2DD68 ON users_oauths (access_token)');
        $this->addSql('CREATE INDEX IDX_88E49D4AA76ED395 ON users_oauths (user_id)');
        $this->addSql('CREATE INDEX IDX_88E49D4A8B8E8428 ON users_oauths (created_at)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88E49D4A772E836A92C4739C ON users_oauths (identifier, provider)');
        $this->addSql('COMMENT ON COLUMN users_oauths.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE users_groups_relations ADD CONSTRAINT FK_12DA6D62A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_groups_relations ADD CONSTRAINT FK_12DA6D621ED93D47 FOREIGN KEY (user_group_id) REFERENCES users_groups (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_oauths ADD CONSTRAINT FK_88E49D4AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users_groups_relations DROP CONSTRAINT FK_12DA6D62A76ED395');
        $this->addSql('ALTER TABLE users_oauths DROP CONSTRAINT FK_88E49D4AA76ED395');
        $this->addSql('ALTER TABLE users_groups_relations DROP CONSTRAINT FK_12DA6D621ED93D47');
        $this->addSql('DROP SEQUENCE users_groups_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_oauths_id_seq CASCADE');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_groups_relations');
        $this->addSql('DROP TABLE users_groups');
        $this->addSql('DROP TABLE users_oauths');
    }
}
