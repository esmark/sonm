<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200403202916 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE media_categories_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE media_collections_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE media_files_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE media_files_transformed_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE media_storages_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE media_categories (id INT NOT NULL, parent_id INT DEFAULT NULL, slug VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_30D688FC727ACA70 ON media_categories (parent_id)');
        $this->addSql('CREATE INDEX IDX_30D688FC989D9B62 ON media_categories (slug)');
        $this->addSql('CREATE TABLE media_collections (id INT NOT NULL, storage_id INT NOT NULL, code VARCHAR(2) NOT NULL, default_filter VARCHAR(255) DEFAULT NULL, upload_filter VARCHAR(255) DEFAULT NULL, params TEXT NOT NULL, relative_path VARCHAR(255) NOT NULL, file_relative_path_pattern VARCHAR(255) NOT NULL, filename_pattern VARCHAR(128) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(190) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_244DA17D77153098 ON media_collections (code)');
        $this->addSql('CREATE INDEX IDX_244DA17D5CC5DB90 ON media_collections (storage_id)');
        $this->addSql('COMMENT ON COLUMN media_collections.params IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE media_files (id INT NOT NULL, category_id INT DEFAULT NULL, user_id VARCHAR(36) DEFAULT NULL, collection VARCHAR(2) NOT NULL, storage VARCHAR(2) NOT NULL, relative_path VARCHAR(100) DEFAULT NULL, filename VARCHAR(100) NOT NULL, original_filename VARCHAR(255) NOT NULL, type VARCHAR(8) NOT NULL, mime_type VARCHAR(32) NOT NULL, original_size INT NOT NULL, size INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_192C84E812469DE2 ON media_files (category_id)');
        $this->addSql('CREATE INDEX IDX_192C84E8FC4D6532 ON media_files (collection)');
        $this->addSql('CREATE INDEX IDX_192C84E8547A1B34 ON media_files (storage)');
        $this->addSql('CREATE INDEX IDX_192C84E8F7C0246A ON media_files (size)');
        $this->addSql('CREATE INDEX IDX_192C84E88CDE5729 ON media_files (type)');
        $this->addSql('CREATE INDEX IDX_192C84E8A76ED395 ON media_files (user_id)');
        $this->addSql('CREATE TABLE media_files_transformed (id INT NOT NULL, file_id INT NOT NULL, collection VARCHAR(2) NOT NULL, storage VARCHAR(2) NOT NULL, filter VARCHAR(32) NOT NULL, size INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1084B87D93CB796C ON media_files_transformed (file_id)');
        $this->addSql('CREATE INDEX IDX_1084B87DFC4D6532 ON media_files_transformed (collection)');
        $this->addSql('CREATE INDEX IDX_1084B87D547A1B34 ON media_files_transformed (storage)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1084B87D7FC45F1D93CB796C ON media_files_transformed (filter, file_id)');
        $this->addSql('CREATE TABLE media_storages (id INT NOT NULL, code VARCHAR(2) NOT NULL, relative_path VARCHAR(255) NOT NULL, provider VARCHAR(255) NOT NULL, arguments TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_358F6F1777153098 ON media_storages (code)');
        $this->addSql('COMMENT ON COLUMN media_storages.arguments IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE media_categories ADD CONSTRAINT FK_30D688FC727ACA70 FOREIGN KEY (parent_id) REFERENCES media_categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media_collections ADD CONSTRAINT FK_244DA17D5CC5DB90 FOREIGN KEY (storage_id) REFERENCES media_storages (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media_files ADD CONSTRAINT FK_192C84E812469DE2 FOREIGN KEY (category_id) REFERENCES media_categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media_files_transformed ADD CONSTRAINT FK_1084B87D93CB796C FOREIGN KEY (file_id) REFERENCES media_files (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE media_categories DROP CONSTRAINT FK_30D688FC727ACA70');
        $this->addSql('ALTER TABLE media_files DROP CONSTRAINT FK_192C84E812469DE2');
        $this->addSql('ALTER TABLE media_files_transformed DROP CONSTRAINT FK_1084B87D93CB796C');
        $this->addSql('ALTER TABLE media_collections DROP CONSTRAINT FK_244DA17D5CC5DB90');
        $this->addSql('DROP SEQUENCE media_categories_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE media_collections_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE media_files_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE media_files_transformed_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE media_storages_id_seq CASCADE');
        $this->addSql('DROP TABLE media_categories');
        $this->addSql('DROP TABLE media_collections');
        $this->addSql('DROP TABLE media_files');
        $this->addSql('DROP TABLE media_files_transformed');
        $this->addSql('DROP TABLE media_storages');
    }
}
