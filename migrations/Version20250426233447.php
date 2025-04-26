<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250426233447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE BL_black_list ADD api_key_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE BL_black_list ADD CONSTRAINT FK_782C61D68BE312B3 FOREIGN KEY (api_key_id) REFERENCES `BL_api_key` (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_782C61D68BE312B3 ON BL_black_list (api_key_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE `BL_black_list` DROP FOREIGN KEY FK_782C61D68BE312B3
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_782C61D68BE312B3 ON `BL_black_list`
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `BL_black_list` DROP api_key_id
        SQL);
    }
}
