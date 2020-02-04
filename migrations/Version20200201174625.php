<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200201174625 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE items_variants_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE products_variants_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER INDEX idx_e11ee94d12469de2 RENAME TO IDX_B3BA5A5A12469DE2');
        $this->addSql('ALTER INDEX idx_e11ee94d8d0c5d40 RENAME TO IDX_B3BA5A5A8D0C5D40');
        $this->addSql('ALTER INDEX idx_e11ee94da76ed395 RENAME TO IDX_B3BA5A5AA76ED395');
        $this->addSql('ALTER INDEX idx_e11ee94d8b8e8428 RENAME TO IDX_B3BA5A5A8B8E8428');
        $this->addSql('ALTER INDEX idx_e11ee94d46c53d4c RENAME TO IDX_B3BA5A5A46C53D4C');
        $this->addSql('ALTER INDEX idx_e11ee94d7b00651c RENAME TO IDX_B3BA5A5A7B00651C');
        $this->addSql('ALTER INDEX uniq_e11ee94da76ed3952b36786b RENAME TO UNIQ_B3BA5A5AA76ED3952B36786B');
        $this->addSql('ALTER TABLE products_variants DROP CONSTRAINT fk_c4e3e32f126f525e');
        $this->addSql('DROP INDEX uniq_c4e3e32f2b36786b126f525e');
        $this->addSql('DROP INDEX idx_c4e3e32f126f525e');
        $this->addSql('ALTER TABLE products_variants RENAME COLUMN item_id TO product_id');
        $this->addSql('ALTER TABLE products_variants ADD CONSTRAINT FK_A69943524584665A FOREIGN KEY (product_id) REFERENCES products (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A69943524584665A ON products_variants (product_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A69943522B36786B4584665A ON products_variants (title, product_id)');
        $this->addSql('ALTER INDEX idx_c4e3e32fa76ed395 RENAME TO IDX_A6994352A76ED395');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE products_variants_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE items_variants_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER INDEX uniq_b3ba5a5aa76ed3952b36786b RENAME TO uniq_e11ee94da76ed3952b36786b');
        $this->addSql('ALTER INDEX idx_b3ba5a5a8d0c5d40 RENAME TO idx_e11ee94d8d0c5d40');
        $this->addSql('ALTER INDEX idx_b3ba5a5a7b00651c RENAME TO idx_e11ee94d7b00651c');
        $this->addSql('ALTER INDEX idx_b3ba5a5a8b8e8428 RENAME TO idx_e11ee94d8b8e8428');
        $this->addSql('ALTER INDEX idx_b3ba5a5a46c53d4c RENAME TO idx_e11ee94d46c53d4c');
        $this->addSql('ALTER INDEX idx_b3ba5a5a12469de2 RENAME TO idx_e11ee94d12469de2');
        $this->addSql('ALTER INDEX idx_b3ba5a5aa76ed395 RENAME TO idx_e11ee94da76ed395');
        $this->addSql('ALTER TABLE products_variants DROP CONSTRAINT FK_A69943524584665A');
        $this->addSql('DROP INDEX IDX_A69943524584665A');
        $this->addSql('DROP INDEX UNIQ_A69943522B36786B4584665A');
        $this->addSql('ALTER TABLE products_variants RENAME COLUMN product_id TO item_id');
        $this->addSql('ALTER TABLE products_variants ADD CONSTRAINT fk_c4e3e32f126f525e FOREIGN KEY (item_id) REFERENCES products (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_c4e3e32f2b36786b126f525e ON products_variants (title, item_id)');
        $this->addSql('CREATE INDEX idx_c4e3e32f126f525e ON products_variants (item_id)');
        $this->addSql('ALTER INDEX idx_a6994352a76ed395 RENAME TO idx_c4e3e32fa76ed395');
    }
}
