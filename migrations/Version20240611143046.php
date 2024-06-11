<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240611143046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE games (id INT AUTO_INCREMENT NOT NULL, tournament_id INT NOT NULL, player1_id INT DEFAULT NULL, player2_id INT DEFAULT NULL, player_winner_id INT DEFAULT NULL, next_game_id INT DEFAULT NULL, stage INT NOT NULL, INDEX IDX_FF232B3133D1A3E7 (tournament_id), INDEX IDX_FF232B31C0990423 (player1_id), INDEX IDX_FF232B31D22CABCD (player2_id), INDEX IDX_FF232B31FDF0E181 (player_winner_id), INDEX IDX_FF232B312601F3A7 (next_game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genders (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player_properties (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player_property_values (id INT AUTO_INCREMENT NOT NULL, player_id INT NOT NULL, property_id INT NOT NULL, value INT NOT NULL, INDEX IDX_9DCAA0D199E6F5DF (player_id), INDEX IDX_9DCAA0D1549213EC (property_id), UNIQUE INDEX unique_property_value (player_id, property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE players (id INT AUTO_INCREMENT NOT NULL, gender_id INT NOT NULL, full_name VARCHAR(100) NOT NULL, hability_level INT NOT NULL, lucky_level INT NOT NULL, INDEX IDX_264E43A6708A0E0 (gender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournaments (id INT AUTO_INCREMENT NOT NULL, gender_id INT NOT NULL, title VARCHAR(50) NOT NULL, date DATE NOT NULL, INDEX IDX_E4BCFAC3708A0E0 (gender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B3133D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments (id)');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B31C0990423 FOREIGN KEY (player1_id) REFERENCES players (id)');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B31D22CABCD FOREIGN KEY (player2_id) REFERENCES players (id)');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B31FDF0E181 FOREIGN KEY (player_winner_id) REFERENCES players (id)');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B312601F3A7 FOREIGN KEY (next_game_id) REFERENCES games (id)');
        $this->addSql('ALTER TABLE player_property_values ADD CONSTRAINT FK_9DCAA0D199E6F5DF FOREIGN KEY (player_id) REFERENCES players (id)');
        $this->addSql('ALTER TABLE player_property_values ADD CONSTRAINT FK_9DCAA0D1549213EC FOREIGN KEY (property_id) REFERENCES player_properties (id)');
        $this->addSql('ALTER TABLE players ADD CONSTRAINT FK_264E43A6708A0E0 FOREIGN KEY (gender_id) REFERENCES genders (id)');
        $this->addSql('ALTER TABLE tournaments ADD CONSTRAINT FK_E4BCFAC3708A0E0 FOREIGN KEY (gender_id) REFERENCES genders (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE games DROP FOREIGN KEY FK_FF232B3133D1A3E7');
        $this->addSql('ALTER TABLE games DROP FOREIGN KEY FK_FF232B31C0990423');
        $this->addSql('ALTER TABLE games DROP FOREIGN KEY FK_FF232B31D22CABCD');
        $this->addSql('ALTER TABLE games DROP FOREIGN KEY FK_FF232B31FDF0E181');
        $this->addSql('ALTER TABLE games DROP FOREIGN KEY FK_FF232B312601F3A7');
        $this->addSql('ALTER TABLE player_property_values DROP FOREIGN KEY FK_9DCAA0D199E6F5DF');
        $this->addSql('ALTER TABLE player_property_values DROP FOREIGN KEY FK_9DCAA0D1549213EC');
        $this->addSql('ALTER TABLE players DROP FOREIGN KEY FK_264E43A6708A0E0');
        $this->addSql('ALTER TABLE tournaments DROP FOREIGN KEY FK_E4BCFAC3708A0E0');
        $this->addSql('DROP TABLE games');
        $this->addSql('DROP TABLE genders');
        $this->addSql('DROP TABLE player_properties');
        $this->addSql('DROP TABLE player_property_values');
        $this->addSql('DROP TABLE players');
        $this->addSql('DROP TABLE tournaments');
    }
}
