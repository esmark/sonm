<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200209214930 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX uniq_728c8815a8ae91de');
        $this->addSql('ALTER TABLE geo_cities ADD citycode VARCHAR(3) NOT NULL');
        $this->addSql('CREATE INDEX IDX_728C8815A8AE91DE ON geo_cities (areacode)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_728C8815174853EA ON geo_cities (citycode)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX IDX_728C8815A8AE91DE');
        $this->addSql('DROP INDEX UNIQ_728C8815174853EA');
        $this->addSql('ALTER TABLE geo_cities DROP citycode');
        $this->addSql('CREATE UNIQUE INDEX uniq_728c8815a8ae91de ON geo_cities (areacode)');
    }
}
