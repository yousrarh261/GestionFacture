<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230113130901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie_ticket (id INT AUTO_INCREMENT NOT NULL, nom_categorie VARCHAR(255) NOT NULL, date_category DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE domain_ticket (id INT AUTO_INCREMENT NOT NULL, nom_domain VARCHAR(255) NOT NULL, date_domain DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, manager_id INT DEFAULT NULL, nom_equipe VARCHAR(255) NOT NULL, date_equipe DATE NOT NULL, INDEX IDX_2449BA15783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat (id INT AUTO_INCREMENT NOT NULL, status_id INT DEFAULT NULL, ticket_id INT DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_55CAF7626BF700BD (status_id), INDEX IDX_55CAF762700047D2 (ticket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, recipient_id INT DEFAULT NULL, ticket_id INT DEFAULT NULL, message LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, is_read TINYINT(1) DEFAULT NULL, INDEX IDX_DB021E96F624B39D (sender_id), INDEX IDX_DB021E96E92F8F78 (recipient_id), INDEX IDX_DB021E96700047D2 (ticket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE priorite_ticket (id INT AUTO_INCREMENT NOT NULL, niveau_priorite VARCHAR(255) NOT NULL, date_priorite DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse_client (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, receiver_id INT DEFAULT NULL, ticket_id INT DEFAULT NULL, date DATETIME NOT NULL, message LONGTEXT NOT NULL, INDEX IDX_1310DDCCF624B39D (sender_id), INDEX IDX_1310DDCCCD53EDB6 (receiver_id), INDEX IDX_1310DDCC700047D2 (ticket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse_solway (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, receiver_id INT DEFAULT NULL, ticket_id INT DEFAULT NULL, date DATETIME NOT NULL, message LONGTEXT NOT NULL, INDEX IDX_863342D9F624B39D (sender_id), INDEX IDX_863342D9CD53EDB6 (receiver_id), INDEX IDX_863342D9700047D2 (ticket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE responsable (id INT AUTO_INCREMENT NOT NULL, nom_responsable VARCHAR(255) DEFAULT NULL, prenom_responsable VARCHAR(255) DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sous_domain_ticket (id INT AUTO_INCREMENT NOT NULL, domain_id INT NOT NULL, nom_ss_domain VARCHAR(255) NOT NULL, date_sous_domain DATETIME NOT NULL, INDEX IDX_C5A4A4E5115F0EE5 (domain_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statut_ticket (id INT AUTO_INCREMENT NOT NULL, etat_ticket VARCHAR(255) NOT NULL, date_status DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, domain_id INT DEFAULT NULL, priorite_id INT DEFAULT NULL, sous_domain_id INT DEFAULT NULL, utilisateurs_id INT DEFAULT NULL, statut_ticket_id INT DEFAULT NULL, affectation_id INT DEFAULT NULL, id_client_id INT DEFAULT NULL, date_ticket DATETIME NOT NULL, titre_ticket VARCHAR(255) NOT NULL, commentaire LONGTEXT NOT NULL, is_closed TINYINT(1) DEFAULT NULL, INDEX IDX_97A0ADA3BCF5E72D (categorie_id), INDEX IDX_97A0ADA3115F0EE5 (domain_id), INDEX IDX_97A0ADA353B4F1DE (priorite_id), INDEX IDX_97A0ADA3A56E5AA (sous_domain_id), INDEX IDX_97A0ADA31E969C5 (utilisateurs_id), INDEX IDX_97A0ADA3D1AED210 (statut_ticket_id), INDEX IDX_97A0ADA36D0ABA22 (affectation_id), INDEX IDX_97A0ADA399DED506 (id_client_id), FULLTEXT INDEX IDX_97A0ADA33D032169 (titre_ticket), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE upload_file (id INT AUTO_INCREMENT NOT NULL, ticket_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_81BB169700047D2 (ticket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateurs (id INT AUTO_INCREMENT NOT NULL, equipe_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, activation_token VARCHAR(255) DEFAULT NULL, reset_token VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, is_valid_by_admin TINYINT(1) NOT NULL, photo VARCHAR(255) DEFAULT NULL, INDEX IDX_497B315E6D861B89 (equipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA15783E3463 FOREIGN KEY (manager_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE etat ADD CONSTRAINT FK_55CAF7626BF700BD FOREIGN KEY (status_id) REFERENCES statut_ticket (id)');
        $this->addSql('ALTER TABLE etat ADD CONSTRAINT FK_55CAF762700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96F624B39D FOREIGN KEY (sender_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96E92F8F78 FOREIGN KEY (recipient_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse_client ADD CONSTRAINT FK_1310DDCCF624B39D FOREIGN KEY (sender_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE reponse_client ADD CONSTRAINT FK_1310DDCCCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE reponse_client ADD CONSTRAINT FK_1310DDCC700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse_solway ADD CONSTRAINT FK_863342D9F624B39D FOREIGN KEY (sender_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE reponse_solway ADD CONSTRAINT FK_863342D9CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE reponse_solway ADD CONSTRAINT FK_863342D9700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE sous_domain_ticket ADD CONSTRAINT FK_C5A4A4E5115F0EE5 FOREIGN KEY (domain_id) REFERENCES domain_ticket (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_ticket (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3115F0EE5 FOREIGN KEY (domain_id) REFERENCES domain_ticket (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA353B4F1DE FOREIGN KEY (priorite_id) REFERENCES priorite_ticket (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3A56E5AA FOREIGN KEY (sous_domain_id) REFERENCES sous_domain_ticket (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA31E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3D1AED210 FOREIGN KEY (statut_ticket_id) REFERENCES statut_ticket (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA399DED506 FOREIGN KEY (id_client_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE upload_file ADD CONSTRAINT FK_81BB169700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_497B315E6D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA15783E3463');
        $this->addSql('ALTER TABLE etat DROP FOREIGN KEY FK_55CAF7626BF700BD');
        $this->addSql('ALTER TABLE etat DROP FOREIGN KEY FK_55CAF762700047D2');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96F624B39D');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96E92F8F78');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96700047D2');
        $this->addSql('ALTER TABLE reponse_client DROP FOREIGN KEY FK_1310DDCCF624B39D');
        $this->addSql('ALTER TABLE reponse_client DROP FOREIGN KEY FK_1310DDCCCD53EDB6');
        $this->addSql('ALTER TABLE reponse_client DROP FOREIGN KEY FK_1310DDCC700047D2');
        $this->addSql('ALTER TABLE reponse_solway DROP FOREIGN KEY FK_863342D9F624B39D');
        $this->addSql('ALTER TABLE reponse_solway DROP FOREIGN KEY FK_863342D9CD53EDB6');
        $this->addSql('ALTER TABLE reponse_solway DROP FOREIGN KEY FK_863342D9700047D2');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE sous_domain_ticket DROP FOREIGN KEY FK_C5A4A4E5115F0EE5');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3BCF5E72D');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3115F0EE5');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA353B4F1DE');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3A56E5AA');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA31E969C5');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3D1AED210');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA399DED506');
        $this->addSql('ALTER TABLE upload_file DROP FOREIGN KEY FK_81BB169700047D2');
        $this->addSql('ALTER TABLE utilisateurs DROP FOREIGN KEY FK_497B315E6D861B89');
        $this->addSql('DROP TABLE categorie_ticket');
        $this->addSql('DROP TABLE domain_ticket');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE etat');
        $this->addSql('DROP TABLE messages');
        $this->addSql('DROP TABLE priorite_ticket');
        $this->addSql('DROP TABLE reponse_client');
        $this->addSql('DROP TABLE reponse_solway');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE responsable');
        $this->addSql('DROP TABLE sous_domain_ticket');
        $this->addSql('DROP TABLE statut_ticket');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE upload_file');
        $this->addSql('DROP TABLE utilisateurs');
    }
}
