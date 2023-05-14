<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pengguna extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 3,
                'auto_increment' => true,
            ],
            'id_jabatan' => [
                'type'           => 'INT',
                'constraint'     => 3,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '35',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '35',
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'profil' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'status_pegawai' => [
                'type'       => 'VARCHAR',
                'constraint' => '35',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'current_timestamp' => true,
            ],
            'created_by' => [
                'type' => 'VARCHAR',
                'constraint' => '35',
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'VARCHAR',
                'constraint' => '35',
                'null' => true,
            ],
            'is_deleted' => [
                'type' => 'TINYINT',
                'constraint' => '1',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'deleted_by' => [
                'type' => 'VARCHAR',
                'constraint' => '35',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('pengguna');
    }

    public function down()
    {
        $this->forge->dropTable('pengguna');
    }
}
