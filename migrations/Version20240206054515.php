<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240206054515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(50) DEFAULT NULL, email VARCHAR(180) NOT NULL, subject VARCHAR(100) DEFAULT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE diet (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_9DE465205E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, diet_id INT DEFAULT NULL, specialty_id INT DEFAULT NULL, user_id INT NOT NULL, name VARCHAR(50) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, description LONGTEXT NOT NULL, email VARCHAR(180) DEFAULT NULL, phone VARCHAR(25) NOT NULL, street_number VARCHAR(10) NOT NULL, street VARCHAR(255) NOT NULL, zip_code VARCHAR(15) NOT NULL, city VARCHAR(255) NOT NULL, is_public TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_EB95123FE1E13ACE (diet_id), INDEX IDX_EB95123F9A353316 (specialty_id), INDEX IDX_EB95123FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialty (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(50) NOT NULL, pseudo VARCHAR(50) DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123FE1E13ACE FOREIGN KEY (diet_id) REFERENCES diet (id)');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123F9A353316 FOREIGN KEY (specialty_id) REFERENCES specialty (id)');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123FE1E13ACE');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123F9A353316');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123FA76ED395');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE diet');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE specialty');
        $this->addSql('DROP TABLE user');
    }
}
