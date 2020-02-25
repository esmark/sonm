<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200224215101 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE shipment_method_cooperative DROP CONSTRAINT fk_3e4e59fd239b3f56');
        $this->addSql('DROP SEQUENCE shipments_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE shipments_methods_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE shippings_methods_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE shippings_methods (id INT NOT NULL, title VARCHAR(100) NOT NULL, service VARCHAR(100) DEFAULT NULL, class VARCHAR(100) DEFAULT NULL, is_enabled BOOLEAN DEFAULT \'true\', created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DA80290C2B36786B ON shippings_methods (title)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DA80290CE19D9AD2 ON shippings_methods (service)');
        $this->addSql('CREATE INDEX IDX_DA80290C46C53D4C ON shippings_methods (is_enabled)');
        $this->addSql('CREATE TABLE shipping_method_cooperative (shipping_method_id INT NOT NULL, cooperative_id INT NOT NULL, PRIMARY KEY(shipping_method_id, cooperative_id))');
        $this->addSql('CREATE INDEX IDX_E27843F5F7D6850 ON shipping_method_cooperative (shipping_method_id)');
        $this->addSql('CREATE INDEX IDX_E27843F8D0C5D40 ON shipping_method_cooperative (cooperative_id)');
        $this->addSql('ALTER TABLE shipping_method_cooperative ADD CONSTRAINT FK_E27843F5F7D6850 FOREIGN KEY (shipping_method_id) REFERENCES shippings_methods (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shipping_method_cooperative ADD CONSTRAINT FK_E27843F8D0C5D40 FOREIGN KEY (cooperative_id) REFERENCES cooperatives (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE shipments');
        $this->addSql('DROP TABLE shipments_methods');
        $this->addSql('DROP TABLE shipment_method_cooperative');
        $this->addSql('ALTER TABLE orders ADD shipping_pick_up_location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD shipping_method_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD shipping_tracking VARCHAR(32) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEACB15751 FOREIGN KEY (shipping_pick_up_location_id) REFERENCES pick_up_locations (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE5F7D6850 FOREIGN KEY (shipping_method_id) REFERENCES shippings_methods (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E52FFDEEACB15751 ON orders (shipping_pick_up_location_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E52FFDEE5F7D6850 ON orders (shipping_method_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE5F7D6850');
        $this->addSql('ALTER TABLE shipping_method_cooperative DROP CONSTRAINT FK_E27843F5F7D6850');
        $this->addSql('DROP SEQUENCE shippings_methods_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE shipments_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE shipments_methods_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE shipments (id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status SMALLINT DEFAULT 0 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_94699ad47b00651c ON shipments (status)');
        $this->addSql('CREATE INDEX idx_94699ad48b8e8428 ON shipments (created_at)');
        $this->addSql('CREATE TABLE shipments_methods (id INT NOT NULL, title VARCHAR(100) NOT NULL, service VARCHAR(100) DEFAULT NULL, class VARCHAR(100) DEFAULT NULL, is_enabled BOOLEAN DEFAULT \'true\', created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_e03898c22b36786b ON shipments_methods (title)');
        $this->addSql('CREATE UNIQUE INDEX uniq_e03898c2e19d9ad2 ON shipments_methods (service)');
        $this->addSql('CREATE INDEX idx_e03898c246c53d4c ON shipments_methods (is_enabled)');
        $this->addSql('CREATE TABLE shipment_method_cooperative (shipment_method_id INT NOT NULL, cooperative_id INT NOT NULL, PRIMARY KEY(shipment_method_id, cooperative_id))');
        $this->addSql('CREATE INDEX idx_3e4e59fd239b3f56 ON shipment_method_cooperative (shipment_method_id)');
        $this->addSql('CREATE INDEX idx_3e4e59fd8d0c5d40 ON shipment_method_cooperative (cooperative_id)');
        $this->addSql('ALTER TABLE shipment_method_cooperative ADD CONSTRAINT fk_3e4e59fd239b3f56 FOREIGN KEY (shipment_method_id) REFERENCES shipments_methods (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shipment_method_cooperative ADD CONSTRAINT fk_3e4e59fd8d0c5d40 FOREIGN KEY (cooperative_id) REFERENCES cooperatives (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE shippings_methods');
        $this->addSql('DROP TABLE shipping_method_cooperative');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEEACB15751');
        $this->addSql('DROP INDEX IDX_E52FFDEEACB15751');
        $this->addSql('DROP INDEX UNIQ_E52FFDEE5F7D6850');
        $this->addSql('ALTER TABLE orders DROP shipping_pick_up_location_id');
        $this->addSql('ALTER TABLE orders DROP shipping_method_id');
        $this->addSql('ALTER TABLE orders DROP shipping_tracking');
    }
}
