<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231115080339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task_list (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, creation_date DATETIME NOT NULL, deleted TINYINT(1) NOT NULL, INDEX IDX_377B6C63F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task_list ADD CONSTRAINT FK_377B6C63F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25224F3C61 FOREIGN KEY (task_list_id) REFERENCES task_list (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_527EDB25224F3C61 ON task (task_list_id)');
        $this->addSql('CREATE INDEX IDX_527EDB25F675F31B ON task (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25224F3C61');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25F675F31B');
        $this->addSql('ALTER TABLE task_list DROP FOREIGN KEY FK_377B6C63F675F31B');
        $this->addSql('DROP TABLE task_list');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_527EDB25224F3C61 ON task');
        $this->addSql('DROP INDEX IDX_527EDB25F675F31B ON task');
    }
}
