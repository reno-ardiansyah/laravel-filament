<?php

namespace Database\Seeders;

use App\Models\Period;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    private mixed $model;
    public function __construct()
    {
        $this->model = Period::class;
    }
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startYear = 2018;
        $endYear = 2030;
    
        for ($i = $startYear; $i <= $endYear; $i++) {
            $periodValue = "{$i}/" . ($i + 1);
    
            $this->model::firstOrCreate(
                ['value' => $periodValue], 
                ['value' => $periodValue] 
            );
        }
    }
}
