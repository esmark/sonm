<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200202020534 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE products_variants_id_seq CASCADE');
        $this->addSql('CREATE TABLE baskets (id INT NOT NULL, product_variant_id UUID NOT NULL, user_id UUID NOT NULL, quantity INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DCFB21EFA80EF684 ON baskets (product_variant_id)');
        $this->addSql('CREATE INDEX IDX_DCFB21EFA76ED395 ON baskets (user_id)');
        $this->addSql('CREATE INDEX IDX_DCFB21EF8B8E8428 ON baskets (created_at)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DCFB21EFA76ED395A80EF684 ON baskets (user_id, product_variant_id)');
        $this->addSql('COMMENT ON COLUMN baskets.product_variant_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN baskets.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE orders_lines (id INT NOT NULL, order_id INT DEFAULT NULL, product_variant_id UUID DEFAULT NULL, quantity INT NOT NULL, price INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EAB23378D9F6D38 ON orders_lines (order_id)');
        $this->addSql('CREATE INDEX IDX_EAB2337A80EF684 ON orders_lines (product_variant_id)');
        $this->addSql('COMMENT ON COLUMN orders_lines.product_variant_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE products (id UUID NOT NULL, category_id INT NOT NULL, cooperative_id INT DEFAULT NULL, tax_rate_id INT DEFAULT NULL, user_id UUID NOT NULL, is_enabled BOOLEAN DEFAULT \'true\' NOT NULL, short_description TEXT DEFAULT NULL, status SMALLINT NOT NULL, measure SMALLINT NOT NULL, width SMALLINT DEFAULT NULL, height SMALLINT DEFAULT NULL, depth SMALLINT DEFAULT NULL, weight NUMERIC(8, 5) DEFAULT NULL, image_id VARCHAR(36) DEFAULT NULL, price_min INT NOT NULL, price_max INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, title VARCHAR(190) DEFAULT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A12469DE2 ON products (category_id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A8D0C5D40 ON products (cooperative_id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5AFDD13F95 ON products (tax_rate_id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5AA76ED395 ON products (user_id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A8B8E8428 ON products (created_at)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A46C53D4C ON products (is_enabled)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A7B00651C ON products (status)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B3BA5A5AA76ED3952B36786B ON products (user_id, title)');
        $this->addSql('COMMENT ON COLUMN products.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN products.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE products_variants (id UUID NOT NULL, cooperative_id INT DEFAULT NULL, product_id UUID DEFAULT NULL, user_id UUID NOT NULL, price INT NOT NULL, sku VARCHAR(20) DEFAULT NULL, status SMALLINT NOT NULL, quantity INT DEFAULT NULL, width SMALLINT DEFAULT NULL, height SMALLINT DEFAULT NULL, depth SMALLINT DEFAULT NULL, weight NUMERIC(8, 5) DEFAULT NULL, quantity_reserved INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(190) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A69943528D0C5D40 ON products_variants (cooperative_id)');
        $this->addSql('CREATE INDEX IDX_A69943524584665A ON products_variants (product_id)');
        $this->addSql('CREATE INDEX IDX_A6994352A76ED395 ON products_variants (user_id)');
        $this->addSql('CREATE INDEX IDX_A69943527B00651C ON products_variants (status)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A69943522B36786B4584665A ON products_variants (title, product_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A6994352F9038C48D0C5D40 ON products_variants (sku, cooperative_id)');
        $this->addSql('COMMENT ON COLUMN products_variants.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN products_variants.product_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN products_variants.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE baskets ADD CONSTRAINT FK_DCFB21EFA80EF684 FOREIGN KEY (product_variant_id) REFERENCES products_variants (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE baskets ADD CONSTRAINT FK_DCFB21EFA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_lines ADD CONSTRAINT FK_EAB23378D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_lines ADD CONSTRAINT FK_EAB2337A80EF684 FOREIGN KEY (product_variant_id) REFERENCES products_variants (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A8D0C5D40 FOREIGN KEY (cooperative_id) REFERENCES cooperatives (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AFDD13F95 FOREIGN KEY (tax_rate_id) REFERENCES tax_rates (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE products_variants ADD CONSTRAINT FK_A69943528D0C5D40 FOREIGN KEY (cooperative_id) REFERENCES cooperatives (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE products_variants ADD CONSTRAINT FK_A69943524584665A FOREIGN KEY (product_id) REFERENCES products (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE products_variants ADD CONSTRAINT FK_A6994352A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE products_variants DROP CONSTRAINT FK_A69943524584665A');
        $this->addSql('ALTER TABLE baskets DROP CONSTRAINT FK_DCFB21EFA80EF684');
        $this->addSql('ALTER TABLE orders_lines DROP CONSTRAINT FK_EAB2337A80EF684');
        $this->addSql('CREATE SEQUENCE products_variants_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP TABLE baskets');
        $this->addSql('DROP TABLE orders_lines');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE products_variants');
    }
}
