<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200427110254 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX uniq_fc59c1a4c6990249');
        $this->addSql('DROP INDEX uniq_fc59c1a4b19e32df');
        $this->addSql('ALTER TABLE geo_countries RENAME COLUMN iso_code_alpha2 TO iso_code_alpha_2');
        $this->addSql('ALTER TABLE geo_countries RENAME COLUMN iso_code_alpha3 TO iso_code_alpha_3');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FC59C1A4DC5BFA90 ON geo_countries (iso_code_alpha_2)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FC59C1A4AB5CCA06 ON geo_countries (iso_code_alpha_3)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_FC59C1A4DC5BFA90');
        $this->addSql('DROP INDEX UNIQ_FC59C1A4AB5CCA06');
        $this->addSql('ALTER TABLE geo_countries RENAME COLUMN iso_code_alpha_2 TO iso_code_alpha2');
        $this->addSql('ALTER TABLE geo_countries RENAME COLUMN iso_code_alpha_3 TO iso_code_alpha3');
        $this->addSql('CREATE UNIQUE INDEX uniq_fc59c1a4c6990249 ON geo_countries (iso_code_alpha3)');
        $this->addSql('CREATE UNIQUE INDEX uniq_fc59c1a4b19e32df ON geo_countries (iso_code_alpha2)');
    }
}
