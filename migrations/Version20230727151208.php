<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230727151208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE day_details (id INT AUTO_INCREMENT NOT NULL, cra_id INT NOT NULL, proj_id INT NOT NULL, daysworked VARCHAR(255) NOT NULL, day DATE NOT NULL, work VARCHAR(255) NOT NULL, hours VARCHAR(255) NOT NULL, INDEX IDX_8AE37B08A62AE3BC (cra_id), INDEX IDX_8AE37B08E1174846 (proj_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE day_details ADD CONSTRAINT FK_8AE37B08A62AE3BC FOREIGN KEY (cra_id) REFERENCES cra (id)');
        $this->addSql('ALTER TABLE day_details ADD CONSTRAINT FK_8AE37B08E1174846 FOREIGN KEY (proj_id) REFERENCES code_projet (id)');
        $this->addSql('ALTER TABLE code_projet CHANGE tva tva INT NOT NULL, CHANGE ttc ttc INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE day_details DROP FOREIGN KEY FK_8AE37B08A62AE3BC');
        $this->addSql('ALTER TABLE day_details DROP FOREIGN KEY FK_8AE37B08E1174846');
        $this->addSql('DROP TABLE day_details');
        $this->addSql('ALTER TABLE code_projet CHANGE tva tva INT DEFAULT NULL, CHANGE ttc ttc INT DEFAULT NULL');
    }
}
