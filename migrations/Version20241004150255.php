<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241029215252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Optimized database schema initialization';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE category (
            id INT AUTO_INCREMENT NOT NULL, 
            name VARCHAR(255) NOT NULL, 
            label VARCHAR(255) NOT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE comment (
            id INT AUTO_INCREMENT NOT NULL, 
            parent_comment_id INT DEFAULT NULL, 
            publisher_id INT NOT NULL, 
            media_id INT NOT NULL, 
            content LONGTEXT NOT NULL, 
            status ENUM("pending", "approved", "rejected") NOT NULL, 
            INDEX IDX_COMMENT_PARENT (parent_comment_id), 
            INDEX IDX_COMMENT_PUBLISHER (publisher_id), 
            INDEX IDX_COMMENT_MEDIA (media_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE episode (
            id INT AUTO_INCREMENT NOT NULL, 
            season_id INT NOT NULL, 
            title VARCHAR(255) NOT NULL, 
            duration INT NOT NULL, 
            released_at DATETIME NOT NULL, 
            INDEX IDX_EPISODE_SEASON (season_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE language (
            id INT AUTO_INCREMENT NOT NULL, 
            name VARCHAR(255) NOT NULL, 
            code VARCHAR(5) NOT NULL UNIQUE, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE media (
            id INT AUTO_INCREMENT NOT NULL, 
            short_description LONGTEXT NOT NULL, 
            long_description LONGTEXT NOT NULL, 
            title VARCHAR(255) NOT NULL, 
            release_date DATETIME NOT NULL, 
            cover_image VARCHAR(255) NOT NULL, 
            staff JSON NOT NULL, 
            casting JSON NOT NULL, 
            discr VARCHAR(50) NOT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE media_category (
            media_id INT NOT NULL, 
            category_id INT NOT NULL, 
            INDEX IDX_MEDIA_CATEGORY_MEDIA (media_id), 
            INDEX IDX_MEDIA_CATEGORY_CATEGORY (category_id), 
            PRIMARY KEY(media_id, category_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE media_language (
            media_id INT NOT NULL, 
            language_id INT NOT NULL, 
            INDEX IDX_MEDIA_LANGUAGE_MEDIA (media_id), 
            INDEX IDX_MEDIA_LANGUAGE_LANGUAGE (language_id), 
            PRIMARY KEY(media_id, language_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE movie (
            id INT NOT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE playlist (
            id INT AUTO_INCREMENT NOT NULL, 
            creator_id INT NOT NULL, 
            name VARCHAR(255) NOT NULL, 
            created_at DATETIME NOT NULL, 
            updated_at DATETIME NOT NULL, 
            INDEX IDX_PLAYLIST_CREATOR (creator_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE playlist_media (
            id INT AUTO_INCREMENT NOT NULL, 
            playlist_id INT NOT NULL, 
            media_id INT NOT NULL, 
            added_at DATETIME NOT NULL, 
            INDEX IDX_PLAYLIST_MEDIA_PLAYLIST (playlist_id), 
            INDEX IDX_PLAYLIST_MEDIA_MEDIA (media_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE playlist_subscription (
            id INT AUTO_INCREMENT NOT NULL, 
            subscriber_id INT NOT NULL, 
            playlist_id INT NOT NULL, 
            subscribed_at DATETIME NOT NULL, 
            INDEX IDX_PLAYLIST_SUBSCRIPTION_SUBSCRIBER (subscriber_id), 
            INDEX IDX_PLAYLIST_SUBSCRIPTION_PLAYLIST (playlist_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE season (
            id INT AUTO_INCREMENT NOT NULL, 
            serie_id INT NOT NULL, 
            number INT NOT NULL, 
            INDEX IDX_SEASON_SERIE (serie_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE serie (
            id INT NOT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE subscription (
            id INT AUTO_INCREMENT NOT NULL, 
            name VARCHAR(255) NOT NULL, 
            price DECIMAL(10, 2) NOT NULL, 
            duration INT NOT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE subscription_history (
            id INT AUTO_INCREMENT NOT NULL, 
            subscriber_id INT NOT NULL, 
            subscription_id INT NOT NULL, 
            start_at DATETIME NOT NULL, 
            end_at DATETIME NOT NULL, 
            INDEX IDX_SUBSCRIPTION_HISTORY_SUBSCRIBER (subscriber_id), 
            INDEX IDX_SUBSCRIPTION_HISTORY_SUBSCRIPTION (subscription_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE user (
            id INT AUTO_INCREMENT NOT NULL, 
            current_subscription_id INT DEFAULT NULL, 
            username VARCHAR(255) NOT NULL UNIQUE, 
            email VARCHAR(255) NOT NULL UNIQUE, 
            password VARCHAR(255) NOT NULL, 
            account_status ENUM("active", "inactive", "banned") NOT NULL, 
            INDEX IDX_USER_SUBSCRIPTION (current_subscription_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE watch_history (
            id INT AUTO_INCREMENT NOT NULL, 
            watcher_id INT NOT NULL, 
            media_id INT NOT NULL, 
            last_watched_at DATETIME NOT NULL, 
            number_of_views INT UNSIGNED NOT NULL, 
            INDEX IDX_WATCH_HISTORY_WATCHER (watcher_id), 
            INDEX IDX_WATCH_HISTORY_MEDIA (media_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_COMMENT_PARENT FOREIGN KEY (parent_comment_id) REFERENCES comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_COMMENT_PUBLISHER FOREIGN KEY (publisher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_COMMENT_MEDIA FOREIGN KEY (media_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_EPISODE_SEASON FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE media_category ADD CONSTRAINT FK_MEDIA_CATEGORY_MEDIA FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_category ADD CONSTRAINT FK_MEDIA_CATEGORY_CATEGORY FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_language ADD CONSTRAINT FK_MEDIA_LANGUAGE_MEDIA FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_language ADD CONSTRAINT FK_MEDIA_LANGUAGE_LANGUAGE FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_MOVIE_MEDIA FOREIGN KEY (id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE playlist ADD CONSTRAINT FK_PLAYLIST_CREATOR FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE playlist_media ADD CONSTRAINT FK_PLAYLIST_MEDIA_PLAYLIST FOREIGN KEY (playlist_id) REFERENCES playlist (id)');
        $this->addSql('ALTER TABLE playlist_media ADD CONSTRAINT FK_PLAYLIST_MEDIA_MEDIA FOREIGN KEY (media_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE playlist_subscription ADD CONSTRAINT FK_PLAYLIST_SUBSCRIPTION_SUBSCRIBER FOREIGN KEY (subscriber_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE playlist_subscription ADD CONSTRAINT FK_PLAYLIST_SUBSCRIPTION_PLAYLIST FOREIGN KEY (playlist_id) REFERENCES playlist (id)');
        $this->addSql('ALTER TABLE season ADD CONSTRAINT FK_SEASON_SERIE FOREIGN KEY (serie_id) REFERENCES serie (id)');
        $this->addSql('ALTER TABLE serie ADD CONSTRAINT FK_SERIE_MEDIA FOREIGN KEY (id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscription_history ADD CONSTRAINT FK_SUBSCRIPTION_HISTORY_SUBSCRIBER FOREIGN KEY (subscriber_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE subscription_history ADD CONSTRAINT FK_SUBSCRIPTION_HISTORY_SUBSCRIPTION FOREIGN KEY (subscription_id) REFERENCES subscription (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_USER_SUBSCRIPTION FOREIGN KEY (current_subscription_id) REFERENCES subscription (id)');
        $this->addSql('ALTER TABLE watch_history ADD CONSTRAINT FK_WATCH_HISTORY_WATCHER FOREIGN KEY (watcher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE watch_history ADD CONSTRAINT FK_WATCH_HISTORY_MEDIA FOREIGN KEY (media_id) REFERENCES media (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE media_category');
        $this->addSql('DROP TABLE media_language');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE playlist');
        $this->addSql('DROP TABLE playlist_media');
        $this->addSql('DROP TABLE playlist_subscription');
        $this->addSql('DROP TABLE season');
        $this->addSql('DROP TABLE serie');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE subscription_history');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE watch_history');
    }
}
