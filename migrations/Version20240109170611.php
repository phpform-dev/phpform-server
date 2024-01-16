<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240109170611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE form_fields (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, form_id INTEGER NOT NULL, type VARCHAR(20) NOT NULL, name VARCHAR(50) NOT NULL, label VARCHAR(100) NOT NULL, hint VARCHAR(255) DEFAULT NULL, is_required BOOLEAN DEFAULT 1 NOT NULL, position INTEGER NOT NULL, validations CLOB DEFAULT NULL --(DC2Type:json)
        , CONSTRAINT FK_7C0B37265FF69B7D FOREIGN KEY (form_id) REFERENCES forms (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_7C0B37265FF69B7D ON form_fields (form_id)');
        $this->addSql('CREATE INDEX form_fields_form_position_idx ON form_fields (form_id, position)');
        $this->addSql('CREATE TABLE forms (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , deleted_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , recaptcha_token VARCHAR(255) DEFAULT NULL, hash VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD3F1BF7D1B862B8 ON forms (hash)');
        $this->addSql('CREATE INDEX forms_hash_idx ON forms (hash)');
        $this->addSql('CREATE INDEX forms_created_at_idx ON forms (created_at)');
        $this->addSql('CREATE INDEX forms_deleted_at_idx ON forms (deleted_at)');
        $this->addSql('CREATE TABLE submissions (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, form_id INTEGER NOT NULL, answers CLOB NOT NULL --(DC2Type:json)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , read_at DATETIME --(DC2Type:datetime_immutable)
        , flagged_at DATETIME --(DC2Type:datetime_immutable)
        , deleted_at DATETIME --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_3F6169F75FF69B7D FOREIGN KEY (form_id) REFERENCES forms (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_3F6169F75FF69B7D ON submissions (form_id)');
        $this->addSql('CREATE INDEX submissions_form_id_idx ON submissions (form_id, created_at)');
        $this->addSql('CREATE TABLE user_permissions (user_id INTEGER NOT NULL, form_id INTEGER NOT NULL, PRIMARY KEY(user_id, form_id), CONSTRAINT FK_84F605FAA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_84F605FA5FF69B7D FOREIGN KEY (form_id) REFERENCES forms (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_84F605FAA76ED395 ON user_permissions (user_id)');
        $this->addSql('CREATE INDEX IDX_84F605FA5FF69B7D ON user_permissions (form_id)');
        $this->addSql('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(30) NOT NULL, email VARCHAR(255) NOT NULL, is_superuser BOOLEAN DEFAULT 1 NOT NULL, password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E95E237E06 ON users (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('CREATE INDEX users_email_idx ON users (email)');
        $this->addSql('CREATE INDEX users_name_idx ON users (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE form_fields');
        $this->addSql('DROP TABLE forms');
        $this->addSql('DROP TABLE submissions');
        $this->addSql('DROP TABLE user_permissions');
        $this->addSql('DROP TABLE users');
    }
}
