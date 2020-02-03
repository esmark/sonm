<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200203011641 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE orders ADD shipping_address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD amount INT NOT NULL');
        $this->addSql('ALTER TABLE orders ADD token VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE orders ADD payment_status SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE orders ADD shipping_status VARCHAR(32) NOT NULL');
        $this->addSql('ALTER TABLE orders ADD ipv4 VARCHAR(15) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD comment TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE4D4CFF2B FOREIGN KEY (shipping_address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E52FFDEE5F37A13B ON orders (token)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE4D4CFF2B ON orders (shipping_address_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE8EA17042 ON orders (amount)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE5E38FE8A ON orders (payment_status)');
        $this->addSql('ALTER TABLE payments ADD payment_method_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE payments ADD order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B325AA1164F FOREIGN KEY (payment_method_id) REFERENCES payments_methods (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B328D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_65D29B325AA1164F ON payments (payment_method_id)');
        $this->addSql('CREATE INDEX IDX_65D29B328D9F6D38 ON payments (order_id)');
        $this->addSql('CREATE INDEX IDX_65D29B327B00651C ON payments (status)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payments DROP CONSTRAINT FK_65D29B325AA1164F');
        $this->addSql('ALTER TABLE payments DROP CONSTRAINT FK_65D29B328D9F6D38');
        $this->addSql('DROP INDEX IDX_65D29B325AA1164F');
        $this->addSql('DROP INDEX IDX_65D29B328D9F6D38');
        $this->addSql('DROP INDEX IDX_65D29B327B00651C');
        $this->addSql('ALTER TABLE payments DROP payment_method_id');
        $this->addSql('ALTER TABLE payments DROP order_id');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE4D4CFF2B');
        $this->addSql('DROP INDEX UNIQ_E52FFDEE5F37A13B');
        $this->addSql('DROP INDEX IDX_E52FFDEE4D4CFF2B');
        $this->addSql('DROP INDEX IDX_E52FFDEE8EA17042');
        $this->addSql('DROP INDEX IDX_E52FFDEE5E38FE8A');
        $this->addSql('ALTER TABLE orders DROP shipping_address_id');
        $this->addSql('ALTER TABLE orders DROP amount');
        $this->addSql('ALTER TABLE orders DROP token');
        $this->addSql('ALTER TABLE orders DROP payment_status');
        $this->addSql('ALTER TABLE orders DROP shipping_status');
        $this->addSql('ALTER TABLE orders DROP ipv4');
        $this->addSql('ALTER TABLE orders DROP comment');
    }
}
