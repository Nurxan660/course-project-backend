<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240530051436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE collection_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE custom_field_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE item_custom_field_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "like_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE refresh_token_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_collection_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE collection_category (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F7CCD7F15E237E06 ON collection_category (name)');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, user_id INT DEFAULT NULL, item_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id)');
        $this->addSql('CREATE INDEX IDX_9474526C126F525E ON comment (item_id)');
        $this->addSql('CREATE TABLE custom_field (id INT NOT NULL, collection_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, is_required BOOLEAN NOT NULL, show_in_table BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_98F8BD31514956FD ON custom_field (collection_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_name_collection ON custom_field (name, collection_id)');
        $this->addSql('CREATE TABLE item (id INT NOT NULL, collection_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1F1B251E514956FD ON item (collection_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_item_name_collection ON item (name, collection_id)');
        $this->addSql('CREATE TABLE item_tag (item_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(item_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_E49CCCB1126F525E ON item_tag (item_id)');
        $this->addSql('CREATE INDEX IDX_E49CCCB1BAD26311 ON item_tag (tag_id)');
        $this->addSql('CREATE TABLE item_custom_field (id INT NOT NULL, item_id INT DEFAULT NULL, custom_field_id INT DEFAULT NULL, value TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_57A240F0126F525E ON item_custom_field (item_id)');
        $this->addSql('CREATE INDEX IDX_57A240F0A1E5E0D4 ON item_custom_field (custom_field_id)');
        $this->addSql('CREATE TABLE "like" (id INT NOT NULL, user_id INT DEFAULT NULL, item_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AC6340B3A76ED395 ON "like" (user_id)');
        $this->addSql('CREATE INDEX IDX_AC6340B3126F525E ON "like" (item_id)');
        $this->addSql('CREATE TABLE refresh_token (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C74F2195C74F2195 ON refresh_token (refresh_token)');
        $this->addSql('CREATE TABLE tag (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(255) NOT NULL, full_name VARCHAR(255) NOT NULL, register_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE INDEX email_idx ON "user" (email)');
        $this->addSql('CREATE TABLE user_collection (id INT NOT NULL, category_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, image_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5B2AA3DE12469DE2 ON user_collection (category_id)');
        $this->addSql('CREATE INDEX IDX_5B2AA3DEA76ED395 ON user_collection (user_id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C126F525E FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE custom_field ADD CONSTRAINT FK_98F8BD31514956FD FOREIGN KEY (collection_id) REFERENCES user_collection (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E514956FD FOREIGN KEY (collection_id) REFERENCES user_collection (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_tag ADD CONSTRAINT FK_E49CCCB1126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_tag ADD CONSTRAINT FK_E49CCCB1BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_custom_field ADD CONSTRAINT FK_57A240F0126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_custom_field ADD CONSTRAINT FK_57A240F0A1E5E0D4 FOREIGN KEY (custom_field_id) REFERENCES custom_field (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "like" ADD CONSTRAINT FK_AC6340B3A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "like" ADD CONSTRAINT FK_AC6340B3126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_collection ADD CONSTRAINT FK_5B2AA3DE12469DE2 FOREIGN KEY (category_id) REFERENCES collection_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_collection ADD CONSTRAINT FK_5B2AA3DEA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE collection_category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE custom_field_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE item_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE item_custom_field_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "like_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE refresh_token_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tag_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE user_collection_id_seq CASCADE');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C126F525E');
        $this->addSql('ALTER TABLE custom_field DROP CONSTRAINT FK_98F8BD31514956FD');
        $this->addSql('ALTER TABLE item DROP CONSTRAINT FK_1F1B251E514956FD');
        $this->addSql('ALTER TABLE item_tag DROP CONSTRAINT FK_E49CCCB1126F525E');
        $this->addSql('ALTER TABLE item_tag DROP CONSTRAINT FK_E49CCCB1BAD26311');
        $this->addSql('ALTER TABLE item_custom_field DROP CONSTRAINT FK_57A240F0126F525E');
        $this->addSql('ALTER TABLE item_custom_field DROP CONSTRAINT FK_57A240F0A1E5E0D4');
        $this->addSql('ALTER TABLE "like" DROP CONSTRAINT FK_AC6340B3A76ED395');
        $this->addSql('ALTER TABLE "like" DROP CONSTRAINT FK_AC6340B3126F525E');
        $this->addSql('ALTER TABLE user_collection DROP CONSTRAINT FK_5B2AA3DE12469DE2');
        $this->addSql('ALTER TABLE user_collection DROP CONSTRAINT FK_5B2AA3DEA76ED395');
        $this->addSql('DROP TABLE collection_category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE custom_field');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_tag');
        $this->addSql('DROP TABLE item_custom_field');
        $this->addSql('DROP TABLE "like"');
        $this->addSql('DROP TABLE refresh_token');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_collection');
    }
}
