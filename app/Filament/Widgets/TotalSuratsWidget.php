<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Surat;

class TotalSuratsWidget extends Widget
{
    protected static string $view = 'filament.widgets.total-surats';

    public int $total = 0;
    public int $masuk = 0;
    public int $keluar = 0;

    public function mount(): void
    {
        $this->total = Surat::count();
        $this->masuk  = Surat::where('jenis_surat', 'masuk')->count();
        $this->keluar = Surat::where('jenis_surat', 'keluar')->count();
    }
}