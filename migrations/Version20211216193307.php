<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211216193307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, team_a_id INT NOT NULL, team_b_id INT NOT NULL, goals_a INT DEFAULT NULL, goals_b INT DEFAULT NULL, INDEX IDX_232B318CEA3FA723 (team_a_id), INDEX IDX_232B318CF88A08CD (team_b_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CEA3FA723 FOREIGN KEY (team_a_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CF88A08CD FOREIGN KEY (team_b_id) REFERENCES team (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C4E0A61F989D9B62 ON team (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP INDEX UNIQ_C4E0A61F989D9B62 ON team');
    }
}
