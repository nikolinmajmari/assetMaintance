<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201209231236 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE check_in (id INT AUTO_INCREMENT NOT NULL, room_id INT NOT NULL, checked_at DATETIME NOT NULL, INDEX IDX_90466CF954177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE check_in_device (check_in_id INT NOT NULL, device_id INT NOT NULL, INDEX IDX_143FAD6B8AA34DF1 (check_in_id), INDEX IDX_143FAD6B94A4C7D4 (device_id), PRIMARY KEY(check_in_id, device_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE home_controller (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE check_in ADD CONSTRAINT FK_90466CF954177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE check_in_device ADD CONSTRAINT FK_143FAD6B8AA34DF1 FOREIGN KEY (check_in_id) REFERENCES check_in (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE check_in_device ADD CONSTRAINT FK_143FAD6B94A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE check_in_device DROP FOREIGN KEY FK_143FAD6B8AA34DF1');
        $this->addSql('DROP TABLE check_in');
        $this->addSql('DROP TABLE check_in_device');
        $this->addSql('DROP TABLE home_controller');
        $this->addSql('ALTER TABLE `user` CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
