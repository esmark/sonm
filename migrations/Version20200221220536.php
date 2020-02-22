<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200221220536 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE cooperatives ADD city_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cooperatives ADD CONSTRAINT FK_DCA860638BAC62AF FOREIGN KEY (city_id) REFERENCES geo_cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_DCA860638BAC62AF ON cooperatives (city_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cooperatives DROP CONSTRAINT FK_DCA860638BAC62AF');
        $this->addSql('DROP INDEX IDX_DCA860638BAC62AF');
        $this->addSql('ALTER TABLE cooperatives DROP city_id');
    }
}
