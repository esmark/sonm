<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200403201029 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE joint_purchases (id UUID NOT NULL, organizer_id UUID DEFAULT NULL, final_date DATE NOT NULL, status SMALLINT DEFAULT 0 NOT NULL, shipping_type SMALLINT DEFAULT 0 NOT NULL, transportation_cost_in_percent INT DEFAULT NULL, telegram_chat_link VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, title VARCHAR(190) DEFAULT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2A27B689876C4DDA ON joint_purchases (organizer_id)');
        $this->addSql('CREATE INDEX IDX_2A27B6898B8E8428 ON joint_purchases (created_at)');
        $this->addSql('COMMENT ON COLUMN joint_purchases.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN joint_purchases.organizer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE joint_purchases_orders (id UUID NOT NULL, joint_purchase_id UUID NOT NULL, user_id UUID NOT NULL, payment INT DEFAULT NULL, shipping_cost INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, comment TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_716FF96A462DC748 ON joint_purchases_orders (joint_purchase_id)');
        $this->addSql('CREATE INDEX IDX_716FF96AA76ED395 ON joint_purchases_orders (user_id)');
        $this->addSql('CREATE INDEX IDX_716FF96A8B8E8428 ON joint_purchases_orders (created_at)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_716FF96A462DC748A76ED395 ON joint_purchases_orders (joint_purchase_id, user_id)');
        $this->addSql('COMMENT ON COLUMN joint_purchases_orders.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN joint_purchases_orders.joint_purchase_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN joint_purchases_orders.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE joint_purchases_orders_lines (id UUID NOT NULL, order_id UUID NOT NULL, product_id UUID NOT NULL, quantity INT NOT NULL, price INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, comment TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1F049B288D9F6D38 ON joint_purchases_orders_lines (order_id)');
        $this->addSql('CREATE INDEX IDX_1F049B284584665A ON joint_purchases_orders_lines (product_id)');
        $this->addSql('CREATE INDEX IDX_1F049B288B8E8428 ON joint_purchases_orders_lines (created_at)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1F049B284584665A8D9F6D38 ON joint_purchases_orders_lines (product_id, order_id)');
        $this->addSql('COMMENT ON COLUMN joint_purchases_orders_lines.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN joint_purchases_orders_lines.order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN joint_purchases_orders_lines.product_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE joint_purchases_products (id UUID NOT NULL, joint_purchase_id UUID NOT NULL, image_id VARCHAR(36) DEFAULT NULL, min_quantity INT NOT NULL, price INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, title VARCHAR(190) DEFAULT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EB38574C462DC748 ON joint_purchases_products (joint_purchase_id)');
        $this->addSql('CREATE INDEX IDX_EB38574C8B8E8428 ON joint_purchases_products (created_at)');
        $this->addSql('CREATE INDEX IDX_EB38574C2B36786B ON joint_purchases_products (title)');
        $this->addSql('COMMENT ON COLUMN joint_purchases_products.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN joint_purchases_products.joint_purchase_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE joint_purchases ADD CONSTRAINT FK_2A27B689876C4DDA FOREIGN KEY (organizer_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE joint_purchases_orders ADD CONSTRAINT FK_716FF96A462DC748 FOREIGN KEY (joint_purchase_id) REFERENCES joint_purchases (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE joint_purchases_orders ADD CONSTRAINT FK_716FF96AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE joint_purchases_orders_lines ADD CONSTRAINT FK_1F049B288D9F6D38 FOREIGN KEY (order_id) REFERENCES joint_purchases_orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE joint_purchases_orders_lines ADD CONSTRAINT FK_1F049B284584665A FOREIGN KEY (product_id) REFERENCES joint_purchases_products (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE joint_purchases_products ADD CONSTRAINT FK_EB38574C462DC748 FOREIGN KEY (joint_purchase_id) REFERENCES joint_purchases (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE joint_purchases_orders DROP CONSTRAINT FK_716FF96A462DC748');
        $this->addSql('ALTER TABLE joint_purchases_products DROP CONSTRAINT FK_EB38574C462DC748');
        $this->addSql('ALTER TABLE joint_purchases_orders_lines DROP CONSTRAINT FK_1F049B288D9F6D38');
        $this->addSql('ALTER TABLE joint_purchases_orders_lines DROP CONSTRAINT FK_1F049B284584665A');
        $this->addSql('DROP TABLE joint_purchases');
        $this->addSql('DROP TABLE joint_purchases_orders');
        $this->addSql('DROP TABLE joint_purchases_orders_lines');
        $this->addSql('DROP TABLE joint_purchases_products');
    }
}
