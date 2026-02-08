<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260208151923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE seo_data (id INT AUTO_INCREMENT NOT NULL, page_identifier VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, canonical_url VARCHAR(255) DEFAULT NULL, meta_robots VARCHAR(50) NOT NULL, og_title VARCHAR(255) DEFAULT NULL, og_description VARCHAR(255) DEFAULT NULL, og_image VARCHAR(255) DEFAULT NULL, og_type VARCHAR(50) NOT NULL, twitter_card VARCHAR(50) NOT NULL, in_sitemap TINYINT NOT NULL, changefreq VARCHAR(20) NOT NULL, priority NUMERIC(2, 1) NOT NULL, is_no_index TINYINT NOT NULL, schema_markup JSON DEFAULT NULL, breadcrumb_title VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_5125E3B27CCEB2F4 (page_identifier), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE seo_data');
    }
}
