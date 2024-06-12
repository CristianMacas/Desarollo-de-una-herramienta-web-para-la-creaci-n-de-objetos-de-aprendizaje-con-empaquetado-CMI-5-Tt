<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220615191638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE atribute (id INT AUTO_INCREMENT NOT NULL, datatype_id INT DEFAULT NULL, pclass_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_6128E945C815A09 (datatype_id), INDEX IDX_6128E9499880302 (pclass_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE datatype (id INT AUTO_INCREMENT NOT NULL, denomination VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operation (id INT AUTO_INCREMENT NOT NULL, datatype_id INT DEFAULT NULL, pclass_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_1981A66D5C815A09 (datatype_id), INDEX IDX_1981A66D99880302 (pclass_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pclass (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE template (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE template_atribute (template_id INT NOT NULL, atribute_id INT NOT NULL, INDEX IDX_5944BEE75DA0FB8 (template_id), INDEX IDX_5944BEE7CAE21197 (atribute_id), PRIMARY KEY(template_id, atribute_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE template_operation (template_id INT NOT NULL, operation_id INT NOT NULL, INDEX IDX_D0D2D0DB5DA0FB8 (template_id), INDEX IDX_D0D2D0DB44AC3583 (operation_id), PRIMARY KEY(template_id, operation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE atribute ADD CONSTRAINT FK_6128E945C815A09 FOREIGN KEY (datatype_id) REFERENCES datatype (id)');
        $this->addSql('ALTER TABLE atribute ADD CONSTRAINT FK_6128E9499880302 FOREIGN KEY (pclass_id) REFERENCES pclass (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D5C815A09 FOREIGN KEY (datatype_id) REFERENCES datatype (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D99880302 FOREIGN KEY (pclass_id) REFERENCES pclass (id)');
        $this->addSql('ALTER TABLE template_atribute ADD CONSTRAINT FK_5944BEE75DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE template_atribute ADD CONSTRAINT FK_5944BEE7CAE21197 FOREIGN KEY (atribute_id) REFERENCES atribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE template_operation ADD CONSTRAINT FK_D0D2D0DB5DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE template_operation ADD CONSTRAINT FK_D0D2D0DB44AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE template_atribute DROP FOREIGN KEY FK_5944BEE7CAE21197');
        $this->addSql('ALTER TABLE atribute DROP FOREIGN KEY FK_6128E945C815A09');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D5C815A09');
        $this->addSql('ALTER TABLE template_operation DROP FOREIGN KEY FK_D0D2D0DB44AC3583');
        $this->addSql('ALTER TABLE atribute DROP FOREIGN KEY FK_6128E9499880302');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D99880302');
        $this->addSql('ALTER TABLE template_atribute DROP FOREIGN KEY FK_5944BEE75DA0FB8');
        $this->addSql('ALTER TABLE template_operation DROP FOREIGN KEY FK_D0D2D0DB5DA0FB8');
        $this->addSql('DROP TABLE atribute');
        $this->addSql('DROP TABLE datatype');
        $this->addSql('DROP TABLE operation');
        $this->addSql('DROP TABLE pclass');
        $this->addSql('DROP TABLE template');
        $this->addSql('DROP TABLE template_atribute');
        $this->addSql('DROP TABLE template_operation');
        $this->addSql('DROP TABLE user');
    }
}
