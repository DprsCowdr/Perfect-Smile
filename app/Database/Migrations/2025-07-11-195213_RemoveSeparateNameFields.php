<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveSeparateNameFields extends Migration
{
    public function up()
    {
        // Drop the separate name columns if they exist
        $this->forge->dropColumn('user', ['firstname', 'lastname', 'middlename']);
    }

    public function down()
    {
        // Add back the separate name columns if needed to rollback
        $fields = [
            'firstname' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'lastname' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'middlename' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ];
        $this->forge->addColumn('user', $fields);
    }
}
