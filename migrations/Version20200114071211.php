<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200114071211 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE cooperatives_pick_up_locations_relations (cooperative_id INT NOT NULL, pick_up_location_id INT NOT NULL, PRIMARY KEY(cooperative_id, pick_up_location_id))');
        $this->addSql('CREATE INDEX IDX_99E29FC78D0C5D40 ON cooperatives_pick_up_locations_relations (cooperative_id)');
        $this->addSql('CREATE INDEX IDX_99E29FC7D3E7C148 ON cooperatives_pick_up_locations_relations (pick_up_location_id)');
        $this->addSql('ALTER TABLE cooperatives_pick_up_locations_relations ADD CONSTRAINT FK_99E29FC78D0C5D40 FOREIGN KEY (cooperative_id) REFERENCES cooperatives (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cooperatives_pick_up_locations_relations ADD CONSTRAINT FK_99E29FC7D3E7C148 FOREIGN KEY (pick_up_location_id) REFERENCES pick_up_locations (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE pick_up_location_cooperative');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE pick_up_location_cooperative (pick_up_location_id INT NOT NULL, cooperative_id INT NOT NULL, PRIMARY KEY(pick_up_location_id, cooperative_id))');
        $this->addSql('CREATE INDEX idx_8ef765208d0c5d40 ON pick_up_location_cooperative (cooperative_id)');
        $this->addSql('CREATE INDEX idx_8ef76520d3e7c148 ON pick_up_location_cooperative (pick_up_location_id)');
        $this->addSql('ALTER TABLE pick_up_location_cooperative ADD CONSTRAINT fk_8ef76520d3e7c148 FOREIGN KEY (pick_up_location_id) REFERENCES pick_up_locations (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pick_up_location_cooperative ADD CONSTRAINT fk_8ef765208d0c5d40 FOREIGN KEY (cooperative_id) REFERENCES cooperatives (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE cooperatives_pick_up_locations_relations');
    }
}
