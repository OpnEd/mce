<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{/**
     * Run the database seeds.
     */
    public function run(): void
    {

        Supplier::create([
            'name' => 'ADS PHARMA SAS',
            'identification' => 900040831,
            'address' => 'CALLE 102 A 70 79',
            'email' => 'adsfarma@adsfarma.com',
            'phonenumber' => '6017516592',
            'data' => [],
        ]);

        Supplier::create([
            'name' => 'DISTRIBUCIONES AXA SA',
            'identification' => 800052534,
            'address' => 'CRA 34  6  70',
            'email' => 'distribucionesaxa@distribucionesaxa.com',
            'phonenumber' => '6017404040',
            'data' => [],
        ]);

        Supplier::create([
            'name' => 'DISTRIBUIDORA FARMACEUTICA ROMA S.A.',
            'identification' => 890901475,
            'address' => 'CALLE 17 A  68 D 37',
            'email' => 'distribuidorafarmaceuticaroma@gmail.com',
            'phonenumber' => '6017469810',
            'data' => [],
        ]);

        Supplier::create([
            'name' => 'ETICOS SERRANO GOMEZ LTDA',
            'identification' => 892300678,
            'address' => 'AUTOPISTA MEDELLIN KIL 3.4 COSTADO NORTE C. EMPRESARIAL METROPOLITANO BODEGA 51',
            'email' => 'eticosserrano@gmail.com',
            'phonenumber' => '6015873010',
            'data' => [],
        ]);

        Supplier::create([
            'name' => 'MEMPHIS PRODUCTS SA',
            'identification' => 800042169,
            'address' => 'CALLE 17  34 64',
            'email' => 'memphis@gmail.com',
            'phonenumber' => '6017447878',
            'data' => [],
        ]);

        Supplier::create([
            'name' => 'PHARMEUROPEA DE COLOMBIA',
            'identification' => 830088135,
            'address' => 'CRA 88 A  64 D 32',
            'email' => 'pharmeuropea@gmail.com',
            'phonenumber' => '6012230562',
            'data' => [],
        ]);

        Supplier::create([
            'name' => 'SALAMANCA RAFAEL ANTONIO',
            'identification' => 17068260,
            'address' => 'TV 93 51 98 PARQUE EMP PUERTA DEL SOL BG 18',
            'email' => 'drogasboyaca@gmail.com',
            'phonenumber' => '6017432597',
            'data' => [],
        ]);

        Supplier::create([
            'name' => 'COOPIDROGAS',
            'identification' => 860026123,
            'address' => 'Autopista Bogotá - Medellín, kilómetro 4.7 costado norte, antes del puente de Siberia',
            'email' => 'copodrogas@gmail.com',
            'phonenumber' => '6014375150',
            'data' => [],
        ]);
    }
}
