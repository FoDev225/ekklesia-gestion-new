<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Church;

class ChurchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Church::create([
            'organisation' => 'AEBECI',
            'organisation_name' => 'Association des Églises Baptistes Evangéliques de Côte d\'Ivoire',
            'district' => 'DISTRICT DU SUD',
            'church_name' => 'YOPOUGON NOUVEAU BUREAU',
            'authorization' => '1660/INT/AT/AG/1 du 24/10/68',

            'address' => '01 BP 11332 Abidjan 01',
            'pastor_phone_number' => '0555145527',
            'secretary_phone_number' => '0141899162',
            'church_email' => 'aebeciyop.nb@gmail.com',
            'pastor_email' => 'tourebe2006@yahoo.fr',
            'localisation' => 'Yopougon Nouveau Bureau derrière le Collège FRELEC',
        ]);
    }
}
