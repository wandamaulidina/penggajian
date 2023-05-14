<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Transaksi extends Migration
{
    public function up()
    {
         $this->forge->addField([
            'id'          => [
                'type'              => 'INT',
                'constraint'        => 11,
                'unsigned'          => true,
                'auto_increment'    => true,
            ],
            'id_pengguna'           => [
                'type'              => 'INT',
                'constraint'        => 11,
                'unsigned'          => true,
            ],
            'tanggal_transaksi'     => [
                'type'              => 'DATE',
            ],
            'jumlah_transaksi'      => [
                'type'              => 'DECIMAL',
                'constraint'        => '12,2',
            ],
            'keterangan_transaksi'  => [
                'type'              => 'TEXT',
                'null'              => true,
            ],
            'created_at' => [
                'type'              => 'DATETIME',
                'null'              => true,
            ],
            'updated_at' => [
                'type'              => 'DATETIME',
                'null'              => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('Transaksi');
    }

    public function down()
    {
        $this->forge->dropTable('Transaksi');

    }
}
