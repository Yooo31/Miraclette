<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240703152618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE orders_elements (id INT AUTO_INCREMENT NOT NULL, main_order_id INT NOT NULL, menu_id INT DEFAULT NULL, INDEX IDX_836CDA9B54BD7C4D (main_order_id), INDEX IDX_836CDA9BCCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE orders_elements ADD CONSTRAINT FK_836CDA9B54BD7C4D FOREIGN KEY (main_order_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE orders_elements ADD CONSTRAINT FK_836CDA9BCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders_elements DROP FOREIGN KEY FK_836CDA9B54BD7C4D');
        $this->addSql('ALTER TABLE orders_elements DROP FOREIGN KEY FK_836CDA9BCCD7E912');
        $this->addSql('DROP TABLE orders_elements');
    }
}
