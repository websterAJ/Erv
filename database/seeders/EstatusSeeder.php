<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\estatus;

class EstatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dtaEstatus             = new estatus();
        $dtaEstatus->nombre     = 'Activo';
        $dtaEstatus->activo     = true;
        $dtaEstatus->save();

        $dtaEstatus             = new estatus();
        $dtaEstatus->nombre     = 'Inactivo';
        $dtaEstatus->activo     = true;
        $dtaEstatus->save();
    }
}
