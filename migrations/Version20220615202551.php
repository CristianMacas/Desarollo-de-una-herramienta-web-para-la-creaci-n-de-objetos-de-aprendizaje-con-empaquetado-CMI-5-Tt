<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220615202551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE template_atribute DROP FOREIGN KEY FK_5944BEE75DA0FB8');
        $this->addSql('ALTER TABLE template_operation DROP FOREIGN KEY FK_D0D2D0DB5DA0FB8');
        $this->addSql('CREATE TABLE pclass_atribute (pclass_id INT NOT NULL, atribute_id INT NOT NULL, INDEX IDX_B2E8E54899880302 (pclass_id), INDEX IDX_B2E8E548CAE21197 (atribute_id), PRIMARY KEY(pclass_id, atribute_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pclass_operation (pclass_id INT NOT NULL, operation_id INT NOT NULL, INDEX IDX_9650C2F999880302 (pclass_id), INDEX IDX_9650C2F944AC3583 (operation_id), PRIMARY KEY(pclass_id, operation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pclass_atribute ADD CONSTRAINT FK_B2E8E54899880302 FOREIGN KEY (pclass_id) REFERENCES pclass (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pclass_atribute ADD CONSTRAINT FK_B2E8E548CAE21197 FOREIGN KEY (atribute_id) REFERENCES atribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pclass_operation ADD CONSTRAINT FK_9650C2F999880302 FOREIGN KEY (pclass_id) REFERENCES pclass (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pclass_operation ADD CONSTRAINT FK_9650C2F944AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE template');
        $this->addSql('DROP TABLE template_atribute');
        $this->addSql('DROP TABLE template_operation');
        $this->addSql('ALTER TABLE atribute DROP FOREIGN KEY FK_6128E9499880302');
        $this->addSql('DROP INDEX IDX_6128E9499880302 ON atribute');
        $this->addSql('ALTER TABLE atribute DROP pclass_id');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D99880302');
        $this->addSql('DROP INDEX IDX_1981A66D99880302 ON operation');
        $this->addSql('ALTER TABLE operation DROP pclass_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE template (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE template_atribute (template_id INT NOT NULL, atribute_id INT NOT NULL, INDEX IDX_5944BEE75DA0FB8 (template_id), INDEX IDX_5944BEE7CAE21197 (atribute_id), PRIMARY KEY(template_id, atribute_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE template_operation (template_id INT NOT NULL, operation_id INT NOT NULL, INDEX IDX_D0D2D0DB5DA0FB8 (template_id), INDEX IDX_D0D2D0DB44AC3583 (operation_id), PRIMARY KEY(template_id, operation_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE template_atribute ADD CONSTRAINT FK_5944BEE75DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE template_atribute ADD CONSTRAINT FK_5944BEE7CAE21197 FOREIGN KEY (atribute_id) REFERENCES atribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE template_operation ADD CONSTRAINT FK_D0D2D0DB44AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE template_operation ADD CONSTRAINT FK_D0D2D0DB5DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE pclass_atribute');
        $this->addSql('DROP TABLE pclass_operation');
        $this->addSql('ALTER TABLE atribute ADD pclass_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE atribute ADD CONSTRAINT FK_6128E9499880302 FOREIGN KEY (pclass_id) REFERENCES pclass (id)');
        $this->addSql('CREATE INDEX IDX_6128E9499880302 ON atribute (pclass_id)');
        $this->addSql('ALTER TABLE operation ADD pclass_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D99880302 FOREIGN KEY (pclass_id) REFERENCES pclass (id)');
        $this->addSql('CREATE INDEX IDX_1981A66D99880302 ON operation (pclass_id)');
    }
}
