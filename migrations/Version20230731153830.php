<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230731153830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, manager_id INT DEFAULT NULL, nom_equipe VARCHAR(255) NOT NULL, date_equipe DATE NOT NULL, INDEX IDX_2449BA15783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA15783E3463 FOREIGN KEY (manager_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE cra_details');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE upload_file');
        $this->addSql('ALTER TABLE cra DROP days_input');
        $this->addSql('ALTER TABLE day_details CHANGE proj_id proj_id INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateurs ADD equipes_gerer_id INT DEFAULT NULL, ADD equipe_id INT DEFAULT NULL, CHANGE reset_token reset_password_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_497B315EE5CF68E FOREIGN KEY (equipes_gerer_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_497B315E6D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
        $this->addSql('CREATE INDEX IDX_497B315EE5CF68E ON utilisateurs (equipes_gerer_id)');
        $this->addSql('CREATE INDEX IDX_497B315E6D861B89 ON utilisateurs (equipe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateurs DROP FOREIGN KEY FK_497B315EE5CF68E');
        $this->addSql('ALTER TABLE utilisateurs DROP FOREIGN KEY FK_497B315E6D861B89');
        $this->addSql('CREATE TABLE cra_details (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, hashed_token VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE upload_file (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA15783E3463');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('ALTER TABLE cra ADD days_input DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE day_details CHANGE proj_id proj_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_497B315EE5CF68E ON utilisateurs');
        $this->addSql('DROP INDEX IDX_497B315E6D861B89 ON utilisateurs');
        $this->addSql('ALTER TABLE utilisateurs DROP equipes_gerer_id, DROP equipe_id, CHANGE reset_password_token reset_token VARCHAR(255) DEFAULT NULL');
    }
}
