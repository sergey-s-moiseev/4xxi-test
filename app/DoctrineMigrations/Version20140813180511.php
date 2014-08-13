<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140813180511 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, facebook_id VARCHAR(255) NOT NULL, photo_filename VARCHAR(128) DEFAULT NULL, updated DATETIME NOT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE friends (user_id INT NOT NULL, friend_id INT NOT NULL, INDEX IDX_21EE7069A76ED395 (user_id), INDEX IDX_21EE70696A5458E8 (friend_id), PRIMARY KEY(user_id, friend_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, message VARCHAR(255) NOT NULL, created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE friends ADD CONSTRAINT FK_21EE7069A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE friends ADD CONSTRAINT FK_21EE70696A5458E8 FOREIGN KEY (friend_id) REFERENCES users (id) ON DELETE CASCADE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE friends DROP FOREIGN KEY FK_21EE7069A76ED395");
        $this->addSql("ALTER TABLE friends DROP FOREIGN KEY FK_21EE70696A5458E8");
        $this->addSql("DROP TABLE users");
        $this->addSql("DROP TABLE friends");
        $this->addSql("DROP TABLE messages");
    }
}
