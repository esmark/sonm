<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200202000945 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE cooperatives_payment_methods_relations (cooperative_id INT NOT NULL, payment_method_id INT NOT NULL, PRIMARY KEY(cooperative_id, payment_method_id))');
        $this->addSql('CREATE INDEX IDX_52426AD88D0C5D40 ON cooperatives_payment_methods_relations (cooperative_id)');
        $this->addSql('CREATE INDEX IDX_52426AD85AA1164F ON cooperatives_payment_methods_relations (payment_method_id)');
        $this->addSql('ALTER TABLE cooperatives_payment_methods_relations ADD CONSTRAINT FK_52426AD88D0C5D40 FOREIGN KEY (cooperative_id) REFERENCES cooperatives (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cooperatives_payment_methods_relations ADD CONSTRAINT FK_52426AD85AA1164F FOREIGN KEY (payment_method_id) REFERENCES payments_methods (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE cooperatives_payment_methods_relations');
    }
}
