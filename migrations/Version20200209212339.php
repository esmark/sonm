<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200209212339 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX uniq_2da9e65e13498b25');
        $this->addSql('DROP INDEX uniq_2da9e65e72a6cab7');
        $this->addSql('DROP INDEX uniq_1571f4f172a6cab7');
        $this->addSql('DROP INDEX uniq_1571f4f113498b25');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE UNIQUE INDEX uniq_1571f4f172a6cab7 ON geo_regions (ifnsfl)');
        $this->addSql('CREATE UNIQUE INDEX uniq_1571f4f113498b25 ON geo_regions (ifnsul)');
        $this->addSql('CREATE UNIQUE INDEX uniq_2da9e65e5c3ab584 ON geo_provinces (terrifnsfl)');
        $this->addSql('CREATE UNIQUE INDEX uniq_2da9e65e13498b25 ON geo_provinces (ifnsul)');
        $this->addSql('CREATE UNIQUE INDEX uniq_2da9e65e72a6cab7 ON geo_provinces (ifnsfl)');
        $this->addSql('CREATE UNIQUE INDEX uniq_2da9e65e3dd5f416 ON geo_provinces (terrifnsul)');
    }
}
