<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200204203108 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE payments DROP CONSTRAINT fk_65d29b325aa1164f');
        $this->addSql('DROP INDEX idx_65d29b325aa1164f');
        $this->addSql('ALTER TABLE payments ADD method_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE payments DROP payment_method_id');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B3219883967 FOREIGN KEY (method_id) REFERENCES payments_methods (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_65D29B3219883967 ON payments (method_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payments DROP CONSTRAINT FK_65D29B3219883967');
        $this->addSql('DROP INDEX IDX_65D29B3219883967');
        $this->addSql('ALTER TABLE payments ADD payment_method_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE payments DROP method_id');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT fk_65d29b325aa1164f FOREIGN KEY (payment_method_id) REFERENCES payments_methods (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_65d29b325aa1164f ON payments (payment_method_id)');
    }
}
