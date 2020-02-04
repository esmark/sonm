<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200201184544 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE products ADD price_min INT DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD price_max INT DEFAULT NULL');
        $this->addSql('ALTER TABLE products_variants ADD cooperative_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE products_variants ADD sku VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE products_variants ADD status SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE products_variants ADD width SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE products_variants ADD height SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE products_variants ADD depth SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE products_variants ADD weight NUMERIC(8, 5) DEFAULT NULL');
        $this->addSql('ALTER TABLE products_variants ADD CONSTRAINT FK_A69943528D0C5D40 FOREIGN KEY (cooperative_id) REFERENCES cooperatives (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A69943528D0C5D40 ON products_variants (cooperative_id)');
        $this->addSql('CREATE INDEX IDX_A69943527B00651C ON products_variants (status)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A6994352F9038C48D0C5D40 ON products_variants (sku, cooperative_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE products DROP price_min');
        $this->addSql('ALTER TABLE products DROP price_max');
        $this->addSql('ALTER TABLE products_variants DROP CONSTRAINT FK_A69943528D0C5D40');
        $this->addSql('DROP INDEX IDX_A69943528D0C5D40');
        $this->addSql('DROP INDEX IDX_A69943527B00651C');
        $this->addSql('DROP INDEX UNIQ_A6994352F9038C48D0C5D40');
        $this->addSql('ALTER TABLE products_variants DROP cooperative_id');
        $this->addSql('ALTER TABLE products_variants DROP sku');
        $this->addSql('ALTER TABLE products_variants DROP status');
        $this->addSql('ALTER TABLE products_variants DROP width');
        $this->addSql('ALTER TABLE products_variants DROP height');
        $this->addSql('ALTER TABLE products_variants DROP depth');
        $this->addSql('ALTER TABLE products_variants DROP weight');
    }
}
