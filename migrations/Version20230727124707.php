<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230727124707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE code_projet (id INT AUTO_INCREMENT NOT NULL, code_projet VARCHAR(255) NOT NULL, date_projet DATETIME NOT NULL, tva INT NOT NULL, ttc INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, manager_id INT DEFAULT NULL, nom_equipe VARCHAR(255) NOT NULL, date_equipe DATE NOT NULL, INDEX IDX_2449BA15783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateurs (id INT AUTO_INCREMENT NOT NULL, equipes_gerer_id INT DEFAULT NULL, equipe_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', reset_password_token VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, activation_token VARCHAR(255) DEFAULT NULL, reset_token VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, is_valid_by_admin TINYINT(1) NOT NULL, photo VARCHAR(255) DEFAULT NULL, INDEX IDX_497B315EE5CF68E (equipes_gerer_id), INDEX IDX_497B315E6D861B89 (equipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA15783E3463 FOREIGN KEY (manager_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_497B315EE5CF68E FOREIGN KEY (equipes_gerer_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_497B315E6D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA15783E3463');
        $this->addSql('ALTER TABLE utilisateurs DROP FOREIGN KEY FK_497B315EE5CF68E');
        $this->addSql('ALTER TABLE utilisateurs DROP FOREIGN KEY FK_497B315E6D861B89');
        $this->addSql('DROP TABLE code_projet');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE utilisateurs');
    }
}
