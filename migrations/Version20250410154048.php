<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410154048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE `BL_black_list` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, username VARCHAR(200) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, domain VARCHAR(255) DEFAULT NULL, ip_address VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, count_username_blocked INT NOT NULL, count_email_blocked INT NOT NULL, count_domain_blocked INT NOT NULL, count_url_blocked INT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_782C61D6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `BL_temp_user` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_F153F440A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `BL_user` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, token VARCHAR(100) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, is_deleted TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `BL_user_setting` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_4CB42D6EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `BL_black_list` ADD CONSTRAINT FK_782C61D6A76ED395 FOREIGN KEY (user_id) REFERENCES `BL_user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `BL_temp_user` ADD CONSTRAINT FK_F153F440A76ED395 FOREIGN KEY (user_id) REFERENCES `BL_user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `BL_user_setting` ADD CONSTRAINT FK_4CB42D6EA76ED395 FOREIGN KEY (user_id) REFERENCES `BL_user` (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE `BL_black_list` DROP FOREIGN KEY FK_782C61D6A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `BL_temp_user` DROP FOREIGN KEY FK_F153F440A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `BL_user_setting` DROP FOREIGN KEY FK_4CB42D6EA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `BL_black_list`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `BL_temp_user`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `BL_user`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `BL_user_setting`
        SQL);
    }
}
