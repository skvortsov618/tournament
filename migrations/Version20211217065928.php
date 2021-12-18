<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211217065928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE division (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE division_team (division_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_59CD206641859289 (division_id), INDEX IDX_59CD2066296CD8AE (team_id), PRIMARY KEY(division_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, team_id INT NOT NULL, division_id INT DEFAULT NULL, score INT DEFAULT NULL, INDEX IDX_136AC113296CD8AE (team_id), INDEX IDX_136AC11341859289 (division_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE division_team ADD CONSTRAINT FK_59CD206641859289 FOREIGN KEY (division_id) REFERENCES division (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE division_team ADD CONSTRAINT FK_59CD2066296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC11341859289 FOREIGN KEY (division_id) REFERENCES division (id)');
        $this->addSql('ALTER TABLE game ADD division_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C41859289 FOREIGN KEY (division_id) REFERENCES division (id)');
        $this->addSql('CREATE INDEX IDX_232B318C41859289 ON game (division_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE division_team DROP FOREIGN KEY FK_59CD206641859289');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C41859289');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC11341859289');
        $this->addSql('DROP TABLE division');
        $this->addSql('DROP TABLE division_team');
        $this->addSql('DROP TABLE result');
        $this->addSql('DROP INDEX IDX_232B318C41859289 ON game');
        $this->addSql('ALTER TABLE game DROP division_id');
    }
}
