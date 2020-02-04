<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200111034352 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE users ADD patronymic VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD phone VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD education VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD schools TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD state VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD activity VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD participate VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD social_links TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD skills TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD has_relative BOOLEAN DEFAULT \'false\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users DROP patronymic');
        $this->addSql('ALTER TABLE users DROP phone');
        $this->addSql('ALTER TABLE users DROP education');
        $this->addSql('ALTER TABLE users DROP schools');
        $this->addSql('ALTER TABLE users DROP state');
        $this->addSql('ALTER TABLE users DROP activity');
        $this->addSql('ALTER TABLE users DROP participate');
        $this->addSql('ALTER TABLE users DROP social_links');
        $this->addSql('ALTER TABLE users DROP skills');
        $this->addSql('ALTER TABLE users DROP has_relative');
    }
}
