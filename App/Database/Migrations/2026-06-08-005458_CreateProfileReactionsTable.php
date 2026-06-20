<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProfileReactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'reactor_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Member who gives the reaction',
            ],
            'profile_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Member who receives the reaction',
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['like', 'love'],
                'default' => 'like',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['reactor_id', 'profile_id']);
        $this->forge->addForeignKey('reactor_id', 'members', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('profile_id', 'members', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('profile_reactions');
    }

    public function down()
    {
        $this->forge->dropTable('profile_reactions');
    }
}