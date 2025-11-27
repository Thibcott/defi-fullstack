<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251120100712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('trip')) {
            return;
        }

        $table = $schema->createTable('trip');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('from_station_id', 'string', ['length' => 10]);
        $table->addColumn('to_station_id', 'string', ['length' => 10]);
        $table->addColumn('analytic_code', 'string', ['length' => 50]);
        $table->addColumn('distance_km', 'float');
        $table->addColumn('created_at', 'datetime_immutable');
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('trip')) {
            $schema->dropTable('trip');
        }
    }
}
