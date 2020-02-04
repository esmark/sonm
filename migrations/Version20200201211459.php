<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200201211459 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE cooperatives ADD tax_rate_default_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cooperatives ADD CONSTRAINT FK_DCA86063961FB768 FOREIGN KEY (tax_rate_default_id) REFERENCES tax_rates (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_DCA86063961FB768 ON cooperatives (tax_rate_default_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cooperatives DROP CONSTRAINT FK_DCA86063961FB768');
        $this->addSql('DROP INDEX IDX_DCA86063961FB768');
        $this->addSql('ALTER TABLE cooperatives DROP tax_rate_default_id');
    }
}
