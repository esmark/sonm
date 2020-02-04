<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200201180014 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE baskets DROP CONSTRAINT fk_dcfb21ef86d881a5');
        $this->addSql('DROP INDEX idx_dcfb21ef86d881a5');
        $this->addSql('DROP INDEX uniq_dcfb21efa76ed39586d881a5');
        $this->addSql('ALTER TABLE baskets ADD product_variant_id INT NOT NULL');
        $this->addSql('ALTER TABLE baskets DROP item_variant_id');
        $this->addSql('ALTER TABLE baskets ADD CONSTRAINT FK_DCFB21EFA80EF684 FOREIGN KEY (product_variant_id) REFERENCES products_variants (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_DCFB21EFA80EF684 ON baskets (product_variant_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DCFB21EFA76ED395A80EF684 ON baskets (user_id, product_variant_id)');
        $this->addSql('ALTER TABLE orders_lines ADD product_variant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders_lines ADD CONSTRAINT FK_EAB2337A80EF684 FOREIGN KEY (product_variant_id) REFERENCES products_variants (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_EAB2337A80EF684 ON orders_lines (product_variant_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE baskets DROP CONSTRAINT FK_DCFB21EFA80EF684');
        $this->addSql('DROP INDEX IDX_DCFB21EFA80EF684');
        $this->addSql('DROP INDEX UNIQ_DCFB21EFA76ED395A80EF684');
        $this->addSql('ALTER TABLE baskets ADD item_variant_id INT NOT NULL');
        $this->addSql('ALTER TABLE baskets DROP product_variant_id');
        $this->addSql('ALTER TABLE baskets ADD CONSTRAINT fk_dcfb21ef86d881a5 FOREIGN KEY (item_variant_id) REFERENCES products_variants (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_dcfb21ef86d881a5 ON baskets (item_variant_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_dcfb21efa76ed39586d881a5 ON baskets (user_id, item_variant_id)');
        $this->addSql('ALTER TABLE orders_lines DROP CONSTRAINT FK_EAB2337A80EF684');
        $this->addSql('DROP INDEX IDX_EAB2337A80EF684');
        $this->addSql('ALTER TABLE orders_lines DROP product_variant_id');
    }
}
