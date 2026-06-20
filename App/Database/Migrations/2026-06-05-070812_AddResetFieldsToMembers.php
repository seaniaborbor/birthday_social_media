<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddResetFieldsToMembers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('members', [
            'reset_token' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'last_login'
            ],
            'reset_expires' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'reset_token'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('members', 'reset_token');
        $this->forge->dropColumn('members', 'reset_expires');
    }
}