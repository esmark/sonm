<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200104014926 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE users (id UUID NOT NULL, username VARCHAR(40) NOT NULL, password VARCHAR(190) NOT NULL, roles TEXT NOT NULL, firstname VARCHAR(30) NOT NULL, lastname VARCHAR(30) NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, latitude NUMERIC(10, 8) DEFAULT NULL, longitude NUMERIC(11, 8) DEFAULT NULL, telegram_user_id INT DEFAULT NULL, telegram_username VARCHAR(100) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_enabled BOOLEAN DEFAULT \'true\', PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9F85E0677 ON users (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9FC28B263 ON users (telegram_user_id)');
        $this->addSql('CREATE INDEX IDX_1483A5E98B8E8428 ON users (created_at)');
        $this->addSql('CREATE INDEX IDX_1483A5E983A00E68 ON users (firstname)');
        $this->addSql('CREATE INDEX IDX_1483A5E93124B5B6 ON users (lastname)');
        $this->addSql('CREATE INDEX IDX_1483A5E9C843FD0B ON users (last_login)');
        $this->addSql('CREATE INDEX IDX_1483A5E946C53D4C ON users (is_enabled)');
        $this->addSql('COMMENT ON COLUMN users.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users.roles IS \'(DC2Type:array)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE users');
    }
}
