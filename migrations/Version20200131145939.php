<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200131145939 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE orders_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tax_rates_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE cooperatives_tax_rates_relations (cooperative_id INT NOT NULL, tax_rate_id INT NOT NULL, PRIMARY KEY(cooperative_id, tax_rate_id))');
        $this->addSql('CREATE INDEX IDX_398CDEA68D0C5D40 ON cooperatives_tax_rates_relations (cooperative_id)');
        $this->addSql('CREATE INDEX IDX_398CDEA6FDD13F95 ON cooperatives_tax_rates_relations (tax_rate_id)');
        $this->addSql('CREATE TABLE orders (id INT NOT NULL, cooperative_id INT DEFAULT NULL, user_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status SMALLINT DEFAULT 0 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E52FFDEE8D0C5D40 ON orders (cooperative_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEEA76ED395 ON orders (user_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE8B8E8428 ON orders (created_at)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE7B00651C ON orders (status)');
        $this->addSql('COMMENT ON COLUMN orders.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE tax_rates (id INT NOT NULL, amount INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(190) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F7AE5E1D8B8E8428 ON tax_rates (created_at)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F7AE5E1D2B36786B ON tax_rates (title)');
        $this->addSql('ALTER TABLE cooperatives_tax_rates_relations ADD CONSTRAINT FK_398CDEA68D0C5D40 FOREIGN KEY (cooperative_id) REFERENCES cooperatives (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cooperatives_tax_rates_relations ADD CONSTRAINT FK_398CDEA6FDD13F95 FOREIGN KEY (tax_rate_id) REFERENCES tax_rates (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE8D0C5D40 FOREIGN KEY (cooperative_id) REFERENCES cooperatives (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP INDEX idx_e11ee94d2b36786b');
        $this->addSql('CREATE INDEX IDX_F149654546C53D4C ON programs (is_enabled)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cooperatives_tax_rates_relations DROP CONSTRAINT FK_398CDEA6FDD13F95');
        $this->addSql('DROP SEQUENCE orders_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tax_rates_id_seq CASCADE');
        $this->addSql('DROP TABLE cooperatives_tax_rates_relations');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE tax_rates');
        $this->addSql('DROP INDEX IDX_F149654546C53D4C');
        $this->addSql('CREATE INDEX idx_e11ee94d2b36786b ON items (title)');
    }
}
