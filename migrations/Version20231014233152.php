<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231014233152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipes (id INT AUTO_INCREMENT NOT NULL, capitaine_id INT NOT NULL, sport_id INT NOT NULL, nom_equipe VARCHAR(100) NOT NULL, niveau INT NOT NULL, adresse_mail VARCHAR(150) NOT NULL, numero_tel VARCHAR(10) NOT NULL, UNIQUE INDEX UNIQ_76F7625A2A10D79E (capitaine_id), INDEX IDX_76F7625AAC78BCF8 (sport_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipes_users (equipes_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_9B917C35737800BA (equipes_id), INDEX IDX_9B917C3567B3B43D (users_id), PRIMARY KEY(equipes_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipes_tournois (equipes_id INT NOT NULL, tournois_id INT NOT NULL, INDEX IDX_2AAA6C52737800BA (equipes_id), INDEX IDX_2AAA6C52752534C (tournois_id), PRIMARY KEY(equipes_id, tournois_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipes_rencontres (equipes_id INT NOT NULL, rencontres_id INT NOT NULL, INDEX IDX_7113CA2A737800BA (equipes_id), INDEX IDX_7113CA2AF471D97B (rencontres_id), PRIMARY KEY(equipes_id, rencontres_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rencontres (id INT AUTO_INCREMENT NOT NULL, gagnant_id INT DEFAULT NULL, appartenance_tournoi_id INT NOT NULL, date_time_at DATETIME NOT NULL, poule INT DEFAULT NULL, tour_journee INT DEFAULT NULL, INDEX IDX_C5F35DFB2F942B8 (gagnant_id), INDEX IDX_C5F35DFB76C66A70 (appartenance_tournoi_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, nom_role VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sports (id INT AUTO_INCREMENT NOT NULL, nom_sport VARCHAR(100) NOT NULL, nb_joueur INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournois (id INT AUTO_INCREMENT NOT NULL, sport_tournois_id INT NOT NULL, vainqueur_id INT DEFAULT NULL, nom_tournoi VARCHAR(100) NOT NULL, start_time_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', duree_tournoi INT NOT NULL, adresse_postal VARCHAR(150) NOT NULL, code_postal VARCHAR(5) NOT NULL, ville_tounoi VARCHAR(100) NOT NULL, nb_equipes INT NOT NULL, etat_tournoi VARCHAR(100) NOT NULL, type_tournoi VARCHAR(100) NOT NULL, INDEX IDX_D7AAF976467170A (sport_tournois_id), INDEX IDX_D7AAF97773C35EE (vainqueur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournois_users (tournois_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_60F5B462752534C (tournois_id), INDEX IDX_60F5B46267B3B43D (users_id), PRIMARY KEY(tournois_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, role_users_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom_user VARCHAR(100) NOT NULL, prenom_user VARCHAR(100) NOT NULL, ville_user VARCHAR(150) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), INDEX IDX_1483A5E94D60520C (role_users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_sports (users_id INT NOT NULL, sports_id INT NOT NULL, INDEX IDX_7C2E778C67B3B43D (users_id), INDEX IDX_7C2E778C54BBBFB7 (sports_id), PRIMARY KEY(users_id, sports_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipes ADD CONSTRAINT FK_76F7625A2A10D79E FOREIGN KEY (capitaine_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE equipes ADD CONSTRAINT FK_76F7625AAC78BCF8 FOREIGN KEY (sport_id) REFERENCES sports (id)');
        $this->addSql('ALTER TABLE equipes_users ADD CONSTRAINT FK_9B917C35737800BA FOREIGN KEY (equipes_id) REFERENCES equipes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipes_users ADD CONSTRAINT FK_9B917C3567B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipes_tournois ADD CONSTRAINT FK_2AAA6C52737800BA FOREIGN KEY (equipes_id) REFERENCES equipes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipes_tournois ADD CONSTRAINT FK_2AAA6C52752534C FOREIGN KEY (tournois_id) REFERENCES tournois (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipes_rencontres ADD CONSTRAINT FK_7113CA2A737800BA FOREIGN KEY (equipes_id) REFERENCES equipes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipes_rencontres ADD CONSTRAINT FK_7113CA2AF471D97B FOREIGN KEY (rencontres_id) REFERENCES rencontres (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rencontres ADD CONSTRAINT FK_C5F35DFB2F942B8 FOREIGN KEY (gagnant_id) REFERENCES equipes (id)');
        $this->addSql('ALTER TABLE rencontres ADD CONSTRAINT FK_C5F35DFB76C66A70 FOREIGN KEY (appartenance_tournoi_id) REFERENCES tournois (id)');
        $this->addSql('ALTER TABLE tournois ADD CONSTRAINT FK_D7AAF976467170A FOREIGN KEY (sport_tournois_id) REFERENCES sports (id)');
        $this->addSql('ALTER TABLE tournois ADD CONSTRAINT FK_D7AAF97773C35EE FOREIGN KEY (vainqueur_id) REFERENCES equipes (id)');
        $this->addSql('ALTER TABLE tournois_users ADD CONSTRAINT FK_60F5B462752534C FOREIGN KEY (tournois_id) REFERENCES tournois (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournois_users ADD CONSTRAINT FK_60F5B46267B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E94D60520C FOREIGN KEY (role_users_id) REFERENCES roles (id)');
        $this->addSql('ALTER TABLE users_sports ADD CONSTRAINT FK_7C2E778C67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_sports ADD CONSTRAINT FK_7C2E778C54BBBFB7 FOREIGN KEY (sports_id) REFERENCES sports (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipes DROP FOREIGN KEY FK_76F7625A2A10D79E');
        $this->addSql('ALTER TABLE equipes DROP FOREIGN KEY FK_76F7625AAC78BCF8');
        $this->addSql('ALTER TABLE equipes_users DROP FOREIGN KEY FK_9B917C35737800BA');
        $this->addSql('ALTER TABLE equipes_users DROP FOREIGN KEY FK_9B917C3567B3B43D');
        $this->addSql('ALTER TABLE equipes_tournois DROP FOREIGN KEY FK_2AAA6C52737800BA');
        $this->addSql('ALTER TABLE equipes_tournois DROP FOREIGN KEY FK_2AAA6C52752534C');
        $this->addSql('ALTER TABLE equipes_rencontres DROP FOREIGN KEY FK_7113CA2A737800BA');
        $this->addSql('ALTER TABLE equipes_rencontres DROP FOREIGN KEY FK_7113CA2AF471D97B');
        $this->addSql('ALTER TABLE rencontres DROP FOREIGN KEY FK_C5F35DFB2F942B8');
        $this->addSql('ALTER TABLE rencontres DROP FOREIGN KEY FK_C5F35DFB76C66A70');
        $this->addSql('ALTER TABLE tournois DROP FOREIGN KEY FK_D7AAF976467170A');
        $this->addSql('ALTER TABLE tournois DROP FOREIGN KEY FK_D7AAF97773C35EE');
        $this->addSql('ALTER TABLE tournois_users DROP FOREIGN KEY FK_60F5B462752534C');
        $this->addSql('ALTER TABLE tournois_users DROP FOREIGN KEY FK_60F5B46267B3B43D');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E94D60520C');
        $this->addSql('ALTER TABLE users_sports DROP FOREIGN KEY FK_7C2E778C67B3B43D');
        $this->addSql('ALTER TABLE users_sports DROP FOREIGN KEY FK_7C2E778C54BBBFB7');
        $this->addSql('DROP TABLE equipes');
        $this->addSql('DROP TABLE equipes_users');
        $this->addSql('DROP TABLE equipes_tournois');
        $this->addSql('DROP TABLE equipes_rencontres');
        $this->addSql('DROP TABLE rencontres');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE sports');
        $this->addSql('DROP TABLE tournois');
        $this->addSql('DROP TABLE tournois_users');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_sports');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
