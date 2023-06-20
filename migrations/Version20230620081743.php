<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230620081743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture CHANGE place_id place_id INT NOT NULL');
        $this->addSql('ALTER TABLE place DROP FOREIGN KEY FK_741D53CDF675F31B');
        $this->addSql('DROP INDEX IDX_741D53CDF675F31B ON place');
        $this->addSql('ALTER TABLE place ADD slug VARCHAR(255) NOT NULL, CHANGE longitude longitude NUMERIC(11, 8) NOT NULL, CHANGE latitude latitude NUMERIC(11, 8) NOT NULL, CHANGE author_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE place ADD CONSTRAINT FK_741D53CDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_741D53CDA76ED395 ON place (user_id)');
        $this->addSql('ALTER TABLE user CHANGE firstname firstname VARCHAR(150) NOT NULL, CHANGE lastname lastname VARCHAR(150) NOT NULL, CHANGE username username VARCHAR(150) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture CHANGE place_id place_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE place DROP FOREIGN KEY FK_741D53CDA76ED395');
        $this->addSql('DROP INDEX IDX_741D53CDA76ED395 ON place');
        $this->addSql('ALTER TABLE place DROP slug, CHANGE latitude latitude VARCHAR(255) DEFAULT NULL, CHANGE longitude longitude VARCHAR(255) DEFAULT NULL, CHANGE user_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE place ADD CONSTRAINT FK_741D53CDF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_741D53CDF675F31B ON place (author_id)');
        $this->addSql('ALTER TABLE user CHANGE firstname firstname VARCHAR(255) NOT NULL, CHANGE lastname lastname VARCHAR(255) NOT NULL, CHANGE username username VARCHAR(255) NOT NULL');
    }
}
