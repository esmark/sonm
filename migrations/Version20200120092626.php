<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200120092626 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE cooperatives_programs_relations (cooperative_id INT NOT NULL, program_id INT NOT NULL, PRIMARY KEY(cooperative_id, program_id))');
        $this->addSql('CREATE INDEX IDX_D222D1E8D0C5D40 ON cooperatives_programs_relations (cooperative_id)');
        $this->addSql('CREATE INDEX IDX_D222D1E3EB8070A ON cooperatives_programs_relations (program_id)');
        $this->addSql('ALTER TABLE cooperatives_programs_relations ADD CONSTRAINT FK_D222D1E8D0C5D40 FOREIGN KEY (cooperative_id) REFERENCES cooperatives (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cooperatives_programs_relations ADD CONSTRAINT FK_D222D1E3EB8070A FOREIGN KEY (program_id) REFERENCES programs (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE cooperatives_programs_relations');
    }
}
