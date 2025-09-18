<?php

namespace App\Filament\Widgets;

use App\Models\Tujuan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SuratPerTujuan extends BaseWidget
{
    protected function getStats(): array
    {
        $tujuans = Tujuan::withCount('surats')->get();

        return $tujuans->map(function ($tujuan) {
            return Stat::make($tujuan->nama, $tujuan->surats_count)
                ->description('Total Surat')
                ->url(route('filament.admin.resources.surats.index', ['tableFilters[tujuan_id][value]' => $tujuan->id]))
                ->color('primary');
        })->toArray();
    }
}
