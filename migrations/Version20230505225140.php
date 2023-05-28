<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230505225140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message (id INT UNSIGNED AUTO_INCREMENT NOT NULL, date DATETIME DEFAULT \'NULL\', etat VARCHAR(20) DEFAULT \'NULL\', type_m VARCHAR(20) NOT NULL, contenu VARCHAR(600) NOT NULL, idconversation INT DEFAULT NULL, idexpediteur INT NOT NULL, idrecipient INT NOT NULL, INDEX fk_idrecipient (idrecipient), INDEX fk_idconversation (idconversation), INDEX fk_idexpediteur (idexpediteur), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messagerie (user INT DEFAULT NULL, id_M INT AUTO_INCREMENT NOT NULL, contenuM VARCHAR(11) NOT NULL, etat VARCHAR(255) NOT NULL, INDEX user (user), PRIMARY KEY(id_M)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, recipient_id INT NOT NULL, title VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, is_read TINYINT(1) NOT NULL, INDEX IDX_DB021E96F624B39D (sender_id), INDEX IDX_DB021E96E92F8F78 (recipient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id_offre INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, image_vehicule VARCHAR(255) NOT NULL, prenom_chauff VARCHAR(255) NOT NULL, num_chauff VARCHAR(255) NOT NULL, date_offre DATETIME NOT NULL, heure TIME NOT NULL, prix_offre INT NOT NULL, depart VARCHAR(255) NOT NULL, destination VARCHAR(255) NOT NULL, places_dispo INT NOT NULL, INDEX secondaryKeyEtudiant (id_user), PRIMARY KEY(id_offre)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre2 (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, description VARCHAR(50) NOT NULL, reduction DOUBLE PRECISION NOT NULL, type VARCHAR(50) NOT NULL, dateD DATETIME NOT NULL, dateF DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id_u INT DEFAULT NULL, id_R INT AUTO_INCREMENT NOT NULL, TypeR VARCHAR(255) NOT NULL, DescriptionR VARCHAR(255) NOT NULL, Objet VARCHAR(255) NOT NULL, DateR DATETIME NOT NULL, etat VARCHAR(255) NOT NULL, INDEX id (id_u), PRIMARY KEY(id_R)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_bus (id_reservation_bus INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, num_place INT NOT NULL, date DATETIME NOT NULL, email VARCHAR(255) NOT NULL, destination VARCHAR(255) NOT NULL, PRIMARY KEY(id_reservation_bus)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_covoiturage (id_reservation INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, pnt_rencontre VARCHAR(255) NOT NULL, distination VARCHAR(255) NOT NULL, nbr_place INT NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id_reservation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, matricule VARCHAR(255) DEFAULT NULL, pfp_u VARCHAR(255) DEFAULT NULL, bio LONGTEXT DEFAULT NULL, active TINYINT(1) DEFAULT NULL, block_date DATETIME DEFAULT NULL, verif_code VARCHAR(255) DEFAULT NULL, email_verif TINYINT(1) DEFAULT NULL, tel_verif TINYINT(1) DEFAULT NULL, appeal LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT FK_14E8F60C8D93D649 FOREIGN KEY (user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96E92F8F78 FOREIGN KEY (recipient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640435F8C041 FOREIGN KEY (id_u) REFERENCES user (id)');
        $this->addSql('ALTER TABLE abonnement CHANGE dateD dateD DATETIME NOT NULL, CHANGE dateF dateF DATETIME NOT NULL');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BBB842C572 FOREIGN KEY (idOffre) REFERENCES offre2 (id)');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF04103C75F FOREIGN KEY (id_offre) REFERENCES offre (id_offre)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF06B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE evenements CHANGE date date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE evenements ADD CONSTRAINT FK_E10AD400BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categories (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF04103C75F');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BBB842C572');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BBA76ED395');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF06B3CA4B');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY FK_14E8F60C8D93D649');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96F624B39D');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96E92F8F78');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F6B3CA4B');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640435F8C041');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE messagerie');
        $this->addSql('DROP TABLE messages');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE offre2');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reservation_bus');
        $this->addSql('DROP TABLE reservation_covoiturage');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE abonnement CHANGE dateD dateD DATE NOT NULL, CHANGE dateF dateF DATE NOT NULL');
        $this->addSql('ALTER TABLE evenements DROP FOREIGN KEY FK_E10AD400BCF5E72D');
        $this->addSql('ALTER TABLE evenements CHANGE date date DATE NOT NULL');
    }
}
