<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240611090022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, tournament_id INT NOT NULL, player1_id INT NOT NULL, player2_id INT NOT NULL, player_winner_id INT NOT NULL, next_game_id INT NOT NULL, stage INT NOT NULL, INDEX IDX_232B318C33D1A3E7 (tournament_id), INDEX IDX_232B318CC0990423 (player1_id), INDEX IDX_232B318CD22CABCD (player2_id), INDEX IDX_232B318CFDF0E181 (player_winner_id), INDEX IDX_232B318C2601F3A7 (next_game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gender (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, gender_id INT NOT NULL, full_name VARCHAR(100) NOT NULL, hability_level INT NOT NULL, lucky_level INT NOT NULL, INDEX IDX_98197A65708A0E0 (gender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player_property (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player_property_value (id INT AUTO_INCREMENT NOT NULL, player_id INT NOT NULL, property_id INT NOT NULL, value INT NOT NULL, INDEX IDX_17BD0ED099E6F5DF (player_id), INDEX IDX_17BD0ED0549213EC (property_id), UNIQUE INDEX unique_player_property (player_id, property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament (id INT AUTO_INCREMENT NOT NULL, gender_id INT NOT NULL, title VARCHAR(50) NOT NULL, date DATE NOT NULL, INDEX IDX_BD5FB8D9708A0E0 (gender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CC0990423 FOREIGN KEY (player1_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CD22CABCD FOREIGN KEY (player2_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CFDF0E181 FOREIGN KEY (player_winner_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C2601F3A7 FOREIGN KEY (next_game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65708A0E0 FOREIGN KEY (gender_id) REFERENCES gender (id)');
        $this->addSql('ALTER TABLE player_property_value ADD CONSTRAINT FK_17BD0ED099E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE player_property_value ADD CONSTRAINT FK_17BD0ED0549213EC FOREIGN KEY (property_id) REFERENCES player_property (id)');
        $this->addSql('ALTER TABLE tournament ADD CONSTRAINT FK_BD5FB8D9708A0E0 FOREIGN KEY (gender_id) REFERENCES gender (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C33D1A3E7');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CC0990423');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CD22CABCD');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CFDF0E181');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C2601F3A7');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65708A0E0');
        $this->addSql('ALTER TABLE player_property_value DROP FOREIGN KEY FK_17BD0ED099E6F5DF');
        $this->addSql('ALTER TABLE player_property_value DROP FOREIGN KEY FK_17BD0ED0549213EC');
        $this->addSql('ALTER TABLE tournament DROP FOREIGN KEY FK_BD5FB8D9708A0E0');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE gender');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE player_property');
        $this->addSql('DROP TABLE player_property_value');
        $this->addSql('DROP TABLE tournament');
    }
}
