<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220922120359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE selection_film (selection_id INT NOT NULL, film_id INT NOT NULL, INDEX IDX_56CA60DFE48EFE78 (selection_id), INDEX IDX_56CA60DF567F5183 (film_id), PRIMARY KEY(selection_id, film_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE selection_film ADD CONSTRAINT FK_56CA60DFE48EFE78 FOREIGN KEY (selection_id) REFERENCES selection (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE selection_film ADD CONSTRAINT FK_56CA60DF567F5183 FOREIGN KEY (film_id) REFERENCES film (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE selection_film DROP FOREIGN KEY FK_56CA60DFE48EFE78');
        $this->addSql('ALTER TABLE selection_film DROP FOREIGN KEY FK_56CA60DF567F5183');
        $this->addSql('DROP TABLE selection_film');
    }
}
