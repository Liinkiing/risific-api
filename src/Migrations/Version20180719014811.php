<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180719014811 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE risific (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, title VARCHAR(191) NOT NULL, slug VARCHAR(191) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chapter (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', risific_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(191) NOT NULL, slug VARCHAR(191) NOT NULL, body LONGTEXT DEFAULT NULL, INDEX IDX_F981B52E271B28DD (risific_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chapter ADD CONSTRAINT FK_F981B52E271B28DD FOREIGN KEY (risific_id) REFERENCES risific (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52E271B28DD');
        $this->addSql('DROP TABLE risific');
        $this->addSql('DROP TABLE chapter');
    }
}
