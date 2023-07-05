<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PendanaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = Storage::disk('local')->get('infak.json');
        $data = json_decode($json, true);

        foreach ($data as $value => $key) {
            DB::table('Pendanaan')->insert([
                [
                    'nama' => $key['nama'],
                    'tanggal' => $key['tanggal'],
                    'infak' => $key['infak']
                ]
            ]);
        }
    }
}
