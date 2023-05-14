<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Komponen extends Migration
{
    public function up()
    {
        
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 3,
                'auto_increment' => true,
            ],
            'id_pengguna' => [
                'type'           => 'INT',
                'constraint'     => 3,
            ],
            'jabatan_pegawai' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
            ],
            'jumlah_jam' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
            ],
            'gaji_pokok' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
            'tunjangan_jabatan' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
            'total_gaji' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
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
        $this->forge->createTable('komponen');
    }

    public function down()
    {
        $this->forge->dropTable('komponen');
    }
}
