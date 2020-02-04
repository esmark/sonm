<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200204185649 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE shipments_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE shipments_methods_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE shipments (id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status SMALLINT DEFAULT 0 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_94699AD48B8E8428 ON shipments (created_at)');
        $this->addSql('CREATE INDEX IDX_94699AD47B00651C ON shipments (status)');
        $this->addSql('CREATE TABLE shipments_methods (id INT NOT NULL, title VARCHAR(100) NOT NULL, service VARCHAR(100) DEFAULT NULL, class VARCHAR(100) DEFAULT NULL, is_enabled BOOLEAN DEFAULT \'true\', created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E03898C22B36786B ON shipments_methods (title)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E03898C2E19D9AD2 ON shipments_methods (service)');
        $this->addSql('CREATE INDEX IDX_E03898C246C53D4C ON shipments_methods (is_enabled)');
        $this->addSql('CREATE TABLE shipment_method_cooperative (shipment_method_id INT NOT NULL, cooperative_id INT NOT NULL, PRIMARY KEY(shipment_method_id, cooperative_id))');
        $this->addSql('CREATE INDEX IDX_3E4E59FD239B3F56 ON shipment_method_cooperative (shipment_method_id)');
        $this->addSql('CREATE INDEX IDX_3E4E59FD8D0C5D40 ON shipment_method_cooperative (cooperative_id)');
        $this->addSql('ALTER TABLE shipment_method_cooperative ADD CONSTRAINT FK_3E4E59FD239B3F56 FOREIGN KEY (shipment_method_id) REFERENCES shipments_methods (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shipment_method_cooperative ADD CONSTRAINT FK_3E4E59FD8D0C5D40 FOREIGN KEY (cooperative_id) REFERENCES cooperatives (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE cooperatives_payment_methods_relations');
        $this->addSql('ALTER TABLE orders ADD checkout_status VARCHAR(16) NOT NULL');
        $this->addSql('ALTER TABLE orders ADD checkout_completed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ALTER token TYPE VARCHAR(16)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE shipment_method_cooperative DROP CONSTRAINT FK_3E4E59FD239B3F56');
        $this->addSql('DROP SEQUENCE shipments_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE shipments_methods_id_seq CASCADE');
        $this->addSql('CREATE TABLE cooperatives_payment_methods_relations (cooperative_id INT NOT NULL, payment_method_id INT NOT NULL, PRIMARY KEY(cooperative_id, payment_method_id))');
        $this->addSql('CREATE INDEX idx_52426ad85aa1164f ON cooperatives_payment_methods_relations (payment_method_id)');
        $this->addSql('CREATE INDEX idx_52426ad88d0c5d40 ON cooperatives_payment_methods_relations (cooperative_id)');
        $this->addSql('ALTER TABLE cooperatives_payment_methods_relations ADD CONSTRAINT fk_52426ad88d0c5d40 FOREIGN KEY (cooperative_id) REFERENCES cooperatives (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cooperatives_payment_methods_relations ADD CONSTRAINT fk_52426ad85aa1164f FOREIGN KEY (payment_method_id) REFERENCES payments_methods (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE shipments');
        $this->addSql('DROP TABLE shipments_methods');
        $this->addSql('DROP TABLE shipment_method_cooperative');
        $this->addSql('ALTER TABLE orders DROP checkout_status');
        $this->addSql('ALTER TABLE orders DROP checkout_completed_at');
        $this->addSql('ALTER TABLE orders ALTER token TYPE VARCHAR(255)');
    }
}
