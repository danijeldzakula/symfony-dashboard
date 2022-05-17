<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220515122527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_527EDB25A76ED395 ON task (user_id)');
        $this->addSql('ALTER TABLE users ADD street VARCHAR(255) NOT NULL, ADD city VARCHAR(255) NOT NULL, ADD country VARCHAR(255) NOT NULL, ADD bank_acc VARCHAR(255) NOT NULL, ADD client VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25A76ED395');
        $this->addSql('DROP INDEX IDX_527EDB25A76ED395 ON task');
        $this->addSql('ALTER TABLE users DROP street, DROP city, DROP country, DROP bank_acc, DROP client');
    }
}
