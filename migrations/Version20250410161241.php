<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410161241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE `BL_api_key` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, version VARCHAR(50) NOT NULL, api_key VARCHAR(255) NOT NULL, name VARCHAR(100) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_991B9C71A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `BL_api_key` ADD CONSTRAINT FK_991B9C71A76ED395 FOREIGN KEY (user_id) REFERENCES `BL_user` (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE `BL_api_key` DROP FOREIGN KEY FK_991B9C71A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `BL_api_key`
        SQL);
    }
}
