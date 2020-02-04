<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200107105121 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE items (id UUID NOT NULL, category_id INT NOT NULL, cooperative_id INT DEFAULT NULL, user_id UUID DEFAULT NULL, is_enabled BOOLEAN DEFAULT \'true\' NOT NULL, price INT NOT NULL, short_description TEXT DEFAULT NULL, status SMALLINT NOT NULL, measure SMALLINT NOT NULL, quantity INT DEFAULT NULL, quantity_reserved INT DEFAULT NULL, image_id VARCHAR(36) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, title VARCHAR(190) DEFAULT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E11EE94D12469DE2 ON items (category_id)');
        $this->addSql('CREATE INDEX IDX_E11EE94D8D0C5D40 ON items (cooperative_id)');
        $this->addSql('CREATE INDEX IDX_E11EE94DA76ED395 ON items (user_id)');
        $this->addSql('CREATE INDEX IDX_E11EE94D8B8E8428 ON items (created_at)');
        $this->addSql('CREATE INDEX IDX_E11EE94D46C53D4C ON items (is_enabled)');
        $this->addSql('CREATE INDEX IDX_E11EE94DCAC822D9 ON items (price)');
        $this->addSql('CREATE INDEX IDX_E11EE94D7B00651C ON items (status)');
        $this->addSql('CREATE INDEX IDX_E11EE94D2B36786B ON items (title)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E11EE94DA76ED3952B36786B ON items (user_id, title)');
        $this->addSql('COMMENT ON COLUMN items.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN items.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE items ADD CONSTRAINT FK_E11EE94D12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE items ADD CONSTRAINT FK_E11EE94D8D0C5D40 FOREIGN KEY (cooperative_id) REFERENCES cooperatives (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE items ADD CONSTRAINT FK_E11EE94DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE items');
    }
}
