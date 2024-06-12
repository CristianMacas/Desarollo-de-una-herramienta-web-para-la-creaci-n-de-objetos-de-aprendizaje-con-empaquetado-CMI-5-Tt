<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220614165536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pclass_atribute (pclass_id INT NOT NULL, atribute_id INT NOT NULL, INDEX IDX_B2E8E54899880302 (pclass_id), INDEX IDX_B2E8E548CAE21197 (atribute_id), PRIMARY KEY(pclass_id, atribute_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pclass_operation (pclass_id INT NOT NULL, operation_id INT NOT NULL, INDEX IDX_9650C2F999880302 (pclass_id), INDEX IDX_9650C2F944AC3583 (operation_id), PRIMARY KEY(pclass_id, operation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pclass_atribute ADD CONSTRAINT FK_B2E8E54899880302 FOREIGN KEY (pclass_id) REFERENCES pclass (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pclass_atribute ADD CONSTRAINT FK_B2E8E548CAE21197 FOREIGN KEY (atribute_id) REFERENCES atribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pclass_operation ADD CONSTRAINT FK_9650C2F999880302 FOREIGN KEY (pclass_id) REFERENCES pclass (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pclass_operation ADD CONSTRAINT FK_9650C2F944AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE pclass_atribute');
        $this->addSql('DROP TABLE pclass_operation');
    }
}
