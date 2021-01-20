<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210120055241 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE short_url ALTER value TYPE VARCHAR(10)');
        $this->addSql('CREATE UNIQUE INDEX ux__short_url__value__original ON short_url (value, original)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX ux__short_url__value__original');
        $this->addSql('ALTER TABLE short_url ALTER value TYPE VARCHAR(55)');
    }
}
