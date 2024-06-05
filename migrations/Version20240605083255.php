<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240605083255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE documents ADD users_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B0728867B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_A2B0728867B3B43D ON documents (users_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B0728867B3B43D');
        $this->addSql('DROP INDEX IDX_A2B0728867B3B43D ON documents');
        $this->addSql('ALTER TABLE documents DROP users_id');
    }
}
