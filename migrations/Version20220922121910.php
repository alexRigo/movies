<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220922121910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE share (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, selection_id INT DEFAULT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_EF069D5AA76ED395 (user_id), INDEX IDX_EF069D5AE48EFE78 (selection_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_share (user_id INT NOT NULL, share_id INT NOT NULL, INDEX IDX_DC46602A76ED395 (user_id), INDEX IDX_DC466022AE63FDB (share_id), PRIMARY KEY(user_id, share_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE share ADD CONSTRAINT FK_EF069D5AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE share ADD CONSTRAINT FK_EF069D5AE48EFE78 FOREIGN KEY (selection_id) REFERENCES selection (id)');
        $this->addSql('ALTER TABLE user_share ADD CONSTRAINT FK_DC46602A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_share ADD CONSTRAINT FK_DC466022AE63FDB FOREIGN KEY (share_id) REFERENCES share (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE share DROP FOREIGN KEY FK_EF069D5AA76ED395');
        $this->addSql('ALTER TABLE share DROP FOREIGN KEY FK_EF069D5AE48EFE78');
        $this->addSql('ALTER TABLE user_share DROP FOREIGN KEY FK_DC46602A76ED395');
        $this->addSql('ALTER TABLE user_share DROP FOREIGN KEY FK_DC466022AE63FDB');
        $this->addSql('DROP TABLE share');
        $this->addSql('DROP TABLE user_share');
    }
}
