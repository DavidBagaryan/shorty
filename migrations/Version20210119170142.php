<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210119170142 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // https://habr.com/ru/company/otus/blog/464253/ datetime_immutable

        $this->addSql('CREATE TABLE auth_token (key UUID NOT NULL, user_id UUID NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(key))');
        $this->addSql('CREATE INDEX ix__auth_token__user_id ON auth_token (user_id)');
        $this->addSql('COMMENT ON COLUMN auth_token.expires_at IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('CREATE TABLE short_url (id UUID NOT NULL, user_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, value VARCHAR(55) NOT NULL, original VARCHAR(555) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX ix__short_url__user_id ON short_url (user_id)');
        $this->addSql('CREATE INDEX ix__short_url__created_at ON short_url (created_at)');
        $this->addSql('COMMENT ON COLUMN short_url.created_at IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('ALTER TABLE auth_token ADD CONSTRAINT FK_9315F04EA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE short_url ADD CONSTRAINT FK_83360531A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE auth_token DROP CONSTRAINT FK_9315F04EA76ED395');
        $this->addSql('ALTER TABLE short_url DROP CONSTRAINT FK_83360531A76ED395');
        $this->addSql('DROP TABLE auth_token');
        $this->addSql('DROP TABLE short_url');
        $this->addSql('DROP TABLE "user"');
    }
}
