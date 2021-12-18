<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211217144742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tournament ADD playoff_id INT NOT NULL');
        $this->addSql('ALTER TABLE tournament ADD CONSTRAINT FK_BD5FB8D9A2B8211C FOREIGN KEY (playoff_id) REFERENCES playoff (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BD5FB8D9A2B8211C ON tournament (playoff_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tournament DROP FOREIGN KEY FK_BD5FB8D9A2B8211C');
        $this->addSql('DROP INDEX UNIQ_BD5FB8D9A2B8211C ON tournament');
        $this->addSql('ALTER TABLE tournament DROP playoff_id');
    }
}
