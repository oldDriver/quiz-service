<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200619042323 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        
        
        $this->addSql('CREATE TYPE enum_result_status AS ENUM(\'new\', \'active\', \'finished\')');
        $this->addSql('ALTER TABLE result ADD status enum_result_status');
        $this->addSql('ALTER TABLE result ALTER status DROP DEFAULT');
        $this->addSql('ALTER TABLE result ALTER status TYPE enum_result_status USING (status::enum_result_status)');
        
        
        
        
        $this->addSql('ALTER TABLE result ADD total SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE result ALTER score TYPE SMALLINT');
        $this->addSql('ALTER TABLE result ALTER score DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

//         $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE result DROP total');
        $this->addSql('ALTER TABLE result DROP status');
        $this->addSql('ALTER TABLE result ALTER score TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE result ALTER score DROP DEFAULT');
        $this->addSql('DROP TYPE enum_result_status');
    }
}
