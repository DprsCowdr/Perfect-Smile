<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveDobFieldFromUserTable extends Migration
{
    public function up()
    {
        // Drop the dob column if it exists
        $this->forge->dropColumn('user', 'dob');
    }

    public function down()
    {
        // Add back the dob column if needed to rollback
        $fields = [
            'dob' => [
                'type' => 'DATE',
                'null' => true,
            ],
        ];
        $this->forge->addColumn('user', $fields);
    }
}
