<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200109074334 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE baskets_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE baskets (id INT NOT NULL, item_id UUID NOT NULL, user_id UUID NOT NULL, quantity INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DCFB21EF126F525E ON baskets (item_id)');
        $this->addSql('CREATE INDEX IDX_DCFB21EFA76ED395 ON baskets (user_id)');
        $this->addSql('CREATE INDEX IDX_DCFB21EF8B8E8428 ON baskets (created_at)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DCFB21EFA76ED395126F525E ON baskets (user_id, item_id)');
        $this->addSql('COMMENT ON COLUMN baskets.item_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN baskets.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE baskets ADD CONSTRAINT FK_DCFB21EF126F525E FOREIGN KEY (item_id) REFERENCES items (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE baskets ADD CONSTRAINT FK_DCFB21EFA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cooperatives_history ALTER user_id SET NOT NULL');
        $this->addSql('ALTER TABLE cooperatives_members ALTER user_id SET NOT NULL');
        $this->addSql('ALTER TABLE items ALTER user_id SET NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE baskets_id_seq CASCADE');
        $this->addSql('DROP TABLE baskets');
        $this->addSql('ALTER TABLE cooperatives_members ALTER user_id DROP NOT NULL');
        $this->addSql('ALTER TABLE cooperatives_history ALTER user_id DROP NOT NULL');
        $this->addSql('ALTER TABLE items ALTER user_id DROP NOT NULL');
    }
}
