<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220614175158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE template (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE template_pclass (template_id INT NOT NULL, pclass_id INT NOT NULL, INDEX IDX_9A614B125DA0FB8 (template_id), INDEX IDX_9A614B1299880302 (pclass_id), PRIMARY KEY(template_id, pclass_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE template_pclass ADD CONSTRAINT FK_9A614B125DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE template_pclass ADD CONSTRAINT FK_9A614B1299880302 FOREIGN KEY (pclass_id) REFERENCES pclass (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE template_pclass DROP FOREIGN KEY FK_9A614B125DA0FB8');
        $this->addSql('DROP TABLE template');
        $this->addSql('DROP TABLE template_pclass');
    }
}
