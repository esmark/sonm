<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200131161158 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE items_variants_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE orders_lines_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE items_variants (id INT NOT NULL, user_id UUID NOT NULL, item_id UUID DEFAULT NULL, price INT NOT NULL, quantity INT DEFAULT NULL, quantity_reserved INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(190) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C4E3E32FA76ED395 ON items_variants (user_id)');
        $this->addSql('CREATE INDEX IDX_C4E3E32F126F525E ON items_variants (item_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C4E3E32F2B36786B126F525E ON items_variants (title, item_id)');
        $this->addSql('COMMENT ON COLUMN items_variants.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN items_variants.item_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE orders_lines (id INT NOT NULL, order_id INT DEFAULT NULL, quantity INT NOT NULL, price INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EAB23378D9F6D38 ON orders_lines (order_id)');
        $this->addSql('ALTER TABLE items_variants ADD CONSTRAINT FK_C4E3E32FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE items_variants ADD CONSTRAINT FK_C4E3E32F126F525E FOREIGN KEY (item_id) REFERENCES items (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_lines ADD CONSTRAINT FK_EAB23378D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DELETE FROM baskets');
        $this->addSql('ALTER TABLE baskets DROP CONSTRAINT fk_dcfb21ef126f525e');
        $this->addSql('DROP INDEX uniq_dcfb21efa76ed395126f525e');
        $this->addSql('DROP INDEX idx_dcfb21ef126f525e');
        $this->addSql('ALTER TABLE baskets ADD item_variant_id INT NOT NULL');
        $this->addSql('ALTER TABLE baskets DROP item_id');
        $this->addSql('ALTER TABLE baskets ADD CONSTRAINT FK_DCFB21EF86D881A5 FOREIGN KEY (item_variant_id) REFERENCES items_variants (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_DCFB21EF86D881A5 ON baskets (item_variant_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DCFB21EFA76ED39586D881A5 ON baskets (user_id, item_variant_id)');
        $this->addSql('DROP INDEX idx_e11ee94dcac822d9');
        $this->addSql('ALTER TABLE items DROP price');
        $this->addSql('ALTER TABLE items DROP quantity');
        $this->addSql('ALTER TABLE items DROP quantity_reserved');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE baskets DROP CONSTRAINT FK_DCFB21EF86D881A5');
        $this->addSql('DROP SEQUENCE items_variants_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE orders_lines_id_seq CASCADE');
        $this->addSql('DROP TABLE items_variants');
        $this->addSql('DROP TABLE orders_lines');
        $this->addSql('DROP INDEX IDX_DCFB21EF86D881A5');
        $this->addSql('DROP INDEX UNIQ_DCFB21EFA76ED39586D881A5');
        $this->addSql('ALTER TABLE baskets ADD item_id UUID NOT NULL');
        $this->addSql('ALTER TABLE baskets DROP item_variant_id');
        $this->addSql('COMMENT ON COLUMN baskets.item_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE baskets ADD CONSTRAINT fk_dcfb21ef126f525e FOREIGN KEY (item_id) REFERENCES items (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_dcfb21efa76ed395126f525e ON baskets (user_id, item_id)');
        $this->addSql('CREATE INDEX idx_dcfb21ef126f525e ON baskets (item_id)');
        $this->addSql('ALTER TABLE items ADD price INT NOT NULL');
        $this->addSql('ALTER TABLE items ADD quantity INT DEFAULT NULL');
        $this->addSql('ALTER TABLE items ADD quantity_reserved INT DEFAULT NULL');
        $this->addSql('CREATE INDEX idx_e11ee94dcac822d9 ON items (price)');
    }
}
