<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221020121301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorite (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, product_id INT DEFAULT NULL, INDEX IDX_68C58ED9A76ED395 (user_id), INDEX IDX_68C58ED94584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED94584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE favoris_product DROP FOREIGN KEY FK_66A0ADA84584665A');
        $this->addSql('ALTER TABLE favoris_product DROP FOREIGN KEY FK_66A0ADA851E8871B');
        $this->addSql('ALTER TABLE favoris_user DROP FOREIGN KEY FK_3E144C2EA76ED395');
        $this->addSql('ALTER TABLE favoris_user DROP FOREIGN KEY FK_3E144C2E51E8871B');
        $this->addSql('DROP TABLE favoris');
        $this->addSql('DROP TABLE favoris_product');
        $this->addSql('DROP TABLE favoris_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favoris (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE favoris_product (favoris_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_66A0ADA84584665A (product_id), INDEX IDX_66A0ADA851E8871B (favoris_id), PRIMARY KEY(favoris_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE favoris_user (favoris_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3E144C2E51E8871B (favoris_id), INDEX IDX_3E144C2EA76ED395 (user_id), PRIMARY KEY(favoris_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE favoris_product ADD CONSTRAINT FK_66A0ADA84584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris_product ADD CONSTRAINT FK_66A0ADA851E8871B FOREIGN KEY (favoris_id) REFERENCES favoris (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris_user ADD CONSTRAINT FK_3E144C2EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris_user ADD CONSTRAINT FK_3E144C2E51E8871B FOREIGN KEY (favoris_id) REFERENCES favoris (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9A76ED395');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED94584665A');
        $this->addSql('DROP TABLE favorite');
    }
}
