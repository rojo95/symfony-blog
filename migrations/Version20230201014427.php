<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201014427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(40) NOT NULL, description CLOB NOT NULL, creation_date DATETIME NOT NULL, watched INTEGER NOT NULL, url VARCHAR(255) NOT NULL, user_id INTEGER NOT NULL, file VARCHAR(255) DEFAULT NULL, update_date DATETIME DEFAULT NULL, status BOOLEAN NOT NULL, type INTEGER NOT NULL, likes INTEGER NOT NULL, dislikes INTEGER NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8A6C8D2B36786B ON post (title)');
        $this->addSql('CREATE TABLE post_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(25) NOT NULL, status BOOLEAN NOT NULL, creation_date DATETIME NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_458B30225E237E06 ON post_type (name)');
        $this->addSql('CREATE TABLE rol (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(25) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E553F375E237E06 ON rol (name)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, photo VARCHAR(255) DEFAULT NULL, name VARCHAR(40) NOT NULL, username VARCHAR(40) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_type');
        $this->addSql('DROP TABLE rol');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
