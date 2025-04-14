<?php

namespace Database\Seeders;

use App\Models\Period;
use App\Models\ClassRoom;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClassRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = ['RPL' => 4, 'TKJ' => 2, 'TSM' => 4, 'TKR' => 4, 'BOGA' => 2, 'BUSANA' => 2];
        $periods = Period::pluck('id')->toArray();

        $data = [];

        foreach ($periods as $periodId) {
            for ($grade = 10; $grade <= 12; $grade++) {
                foreach ($sections as $sectionBase => $totalSections) {
                    for ($i = 1; $i <= $totalSections; $i++) {
                        $data[] = [
                            'period_id' => $periodId,
                            'grade'     => $grade,
                            'section'   => "{$sectionBase} {$i}",
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }

        $existing = ClassRoom::select('period_id', 'grade', 'section')
            ->get()
            ->map(fn($row) => "{$row->period_id}-{$row->grade}-{$row->section}")
            ->toArray();

        $filteredData = collect($data)->reject(function ($item) use ($existing) {
            $key = "{$item['period_id']}-{$item['grade']}-{$item['section']}";
            return in_array($key, $existing);
        })->values()->toArray();

        ClassRoom::insert($filteredData);

        echo count($filteredData) . " new records inserted.\n";
    }
}
