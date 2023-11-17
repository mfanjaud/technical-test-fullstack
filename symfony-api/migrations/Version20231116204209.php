<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231116204209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task CHANGE creation_date created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE task_list CHANGE creation_date created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE creation_date created_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_list CHANGE created_at creation_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE task CHANGE created_at creation_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE created_at creation_date DATETIME NOT NULL');
    }
}
