<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211217102057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE playoff (id INT AUTO_INCREMENT NOT NULL, quarter1_id INT DEFAULT NULL, quarter2_id INT DEFAULT NULL, quarter3_id INT DEFAULT NULL, quarter4_id INT DEFAULT NULL, half1_id INT DEFAULT NULL, half2_id INT DEFAULT NULL, final_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_68AAF5CEF3BD2B91 (quarter1_id), UNIQUE INDEX UNIQ_68AAF5CEE108847F (quarter2_id), UNIQUE INDEX UNIQ_68AAF5CE59B4E31A (quarter3_id), UNIQUE INDEX UNIQ_68AAF5CEC463DBA3 (quarter4_id), UNIQUE INDEX UNIQ_68AAF5CEF33EED1C (half1_id), UNIQUE INDEX UNIQ_68AAF5CEE18B42F2 (half2_id), UNIQUE INDEX UNIQ_68AAF5CE13D41B2D (final_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playoff_team (playoff_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_7935F32A2B8211C (playoff_id), INDEX IDX_7935F32296CD8AE (team_id), PRIMARY KEY(playoff_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament (id INT AUTO_INCREMENT NOT NULL, division_a_id INT NOT NULL, division_b_id INT NOT NULL, tournament_name VARCHAR(50) DEFAULT NULL, UNIQUE INDEX UNIQ_BD5FB8D9A6F3D521 (division_a_id), UNIQUE INDEX UNIQ_BD5FB8D9B4467ACF (division_b_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament_team (tournament_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_F36D142133D1A3E7 (tournament_id), INDEX IDX_F36D1421296CD8AE (team_id), PRIMARY KEY(tournament_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE playoff ADD CONSTRAINT FK_68AAF5CEF3BD2B91 FOREIGN KEY (quarter1_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE playoff ADD CONSTRAINT FK_68AAF5CEE108847F FOREIGN KEY (quarter2_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE playoff ADD CONSTRAINT FK_68AAF5CE59B4E31A FOREIGN KEY (quarter3_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE playoff ADD CONSTRAINT FK_68AAF5CEC463DBA3 FOREIGN KEY (quarter4_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE playoff ADD CONSTRAINT FK_68AAF5CEF33EED1C FOREIGN KEY (half1_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE playoff ADD CONSTRAINT FK_68AAF5CEE18B42F2 FOREIGN KEY (half2_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE playoff ADD CONSTRAINT FK_68AAF5CE13D41B2D FOREIGN KEY (final_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE playoff_team ADD CONSTRAINT FK_7935F32A2B8211C FOREIGN KEY (playoff_id) REFERENCES playoff (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE playoff_team ADD CONSTRAINT FK_7935F32296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournament ADD CONSTRAINT FK_BD5FB8D9A6F3D521 FOREIGN KEY (division_a_id) REFERENCES division (id)');
        $this->addSql('ALTER TABLE tournament ADD CONSTRAINT FK_BD5FB8D9B4467ACF FOREIGN KEY (division_b_id) REFERENCES division (id)');
        $this->addSql('ALTER TABLE tournament_team ADD CONSTRAINT FK_F36D142133D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournament_team ADD CONSTRAINT FK_F36D1421296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE result ADD playoff_id INT DEFAULT NULL, ADD tournament_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113A2B8211C FOREIGN KEY (playoff_id) REFERENCES playoff (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC11333D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('CREATE INDEX IDX_136AC113A2B8211C ON result (playoff_id)');
        $this->addSql('CREATE INDEX IDX_136AC11333D1A3E7 ON result (tournament_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playoff_team DROP FOREIGN KEY FK_7935F32A2B8211C');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113A2B8211C');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC11333D1A3E7');
        $this->addSql('ALTER TABLE tournament_team DROP FOREIGN KEY FK_F36D142133D1A3E7');
        $this->addSql('DROP TABLE playoff');
        $this->addSql('DROP TABLE playoff_team');
        $this->addSql('DROP TABLE tournament');
        $this->addSql('DROP TABLE tournament_team');
        $this->addSql('DROP INDEX IDX_136AC113A2B8211C ON result');
        $this->addSql('DROP INDEX IDX_136AC11333D1A3E7 ON result');
        $this->addSql('ALTER TABLE result DROP playoff_id, DROP tournament_id');
    }
}
