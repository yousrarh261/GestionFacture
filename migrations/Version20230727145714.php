<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230727145714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cra (id INT AUTO_INCREMENT NOT NULL, code_projet_id INT NOT NULL, date DATE NOT NULL, month VARCHAR(255) NOT NULL, INDEX IDX_926CE6D1AC84FA4A (code_projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cra ADD CONSTRAINT FK_926CE6D1AC84FA4A FOREIGN KEY (code_projet_id) REFERENCES code_projet (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cra DROP FOREIGN KEY FK_926CE6D1AC84FA4A');
        $this->addSql('DROP TABLE cra');
    }
}
