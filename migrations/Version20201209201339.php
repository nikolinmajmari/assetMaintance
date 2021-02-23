<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201209201339 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE building (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE device (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, room_id INT NOT NULL, status SMALLINT NOT NULL, INDEX IDX_92FB68EC54C8C93 (type_id), INDEX IDX_92FB68E54177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE device_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, building_id INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, floor INT DEFAULT NULL, INDEX IDX_729F519B4D2A7E12 (building_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68EC54C8C93 FOREIGN KEY (type_id) REFERENCES device_type (id)');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68E54177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B4D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519B4D2A7E12');
        $this->addSql('ALTER TABLE device DROP FOREIGN KEY FK_92FB68EC54C8C93');
        $this->addSql('ALTER TABLE device DROP FOREIGN KEY FK_92FB68E54177093');
        $this->addSql('DROP TABLE building');
        $this->addSql('DROP TABLE device');
        $this->addSql('DROP TABLE device_type');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE `user`');
    }
}
