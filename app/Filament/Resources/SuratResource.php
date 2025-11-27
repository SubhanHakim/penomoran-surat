<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratResource\Pages;
use App\Models\Surat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SuratResource extends Resource
{
    protected static ?string $model = Surat::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'Surat';
    protected static ?string $pluralModelLabel = 'Surat';
    protected static ?string $modelLabel = 'Surat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Jenis surat (masuk / keluar)
                Forms\Components\Select::make('jenis_surat')
                    ->label('Jenis Surat')
                    ->options([
                        'masuk' => 'Surat Masuk',
                        'keluar' => 'Surat Keluar',
                    ])
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state === 'masuk') {
                            $set('kode_klafifikasi', null);
                            $set('bulan', null);
                            $set('tahun', null);
                            $set('nomor_surat', null);
                        } else {
                            $set('perihal', null);
                            $set('ditujukan_kepada', null);
                        }
                    }),

                // Daftar pengirim (umum)
                Forms\Components\Select::make('daftar_pengirim')
                    ->label('Daftar Pengirim')
                    ->options([
                        'DPRD' => 'DPRD',
                        'Sekretariat DPRD' => 'Sekretariat DPRD',
                    ])
                    ->required(),

                // SURAT MASUK fields
                Forms\Components\TextInput::make('ditujukan_kepada')
                    ->label('Ditujukan Kepada')
                    ->visible(fn(callable $get) => $get('jenis_surat') === 'masuk')
                    ->required(fn(callable $get) => $get('jenis_surat') === 'masuk'),

                Forms\Components\TextInput::make('perihal')
                    ->label('Perihal')
                    ->visible(fn(callable $get) => $get('jenis_surat') === 'masuk')
                    ->required(fn(callable $get) => $get('jenis_surat') === 'masuk'),

                // SURAT KELUAR fields
                Forms\Components\Select::make('kode_klafifikasi')
                    ->label('Kode Klasifikasi')
                    ->options([
                        '000' => '000',
                        '100' => '100',
                        '200' => '200',
                        '300' => '300',
                        '400' => '400',
                        '500' => '500',
                        '600' => '600',
                        '700' => '700',
                        '800' => '800',
                        '900' => '900',
                    ])
                    ->visible(fn(callable $get) => $get('jenis_surat') === 'keluar')
                    ->required(fn(callable $get) => $get('jenis_surat') === 'keluar')
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set) {
                        // set nomor_surat otomatis berdasarkan kode terpilih (kode.seq)
                        $next = intval(Surat::generateNextNumber($state));
                        $set('nomor_surat', "{$state}.{$next}");
                    }),

                // final nomor yang disimpan — otomatis di-set dari kode_klafifikasi, user tidak perlu input sub-urut
                Forms\Components\TextInput::make('nomor_surat')
                    ->label('Nomor Surat (urut)')
                    ->default(fn(callable $get) => (string) Surat::generateNextNumber($get('kode_klafifikasi') ?? null))
                    ->disabled()
                    ->unique(ignoreRecord: true)
                    ->visible(fn(callable $get) => $get('jenis_surat') === 'keluar')
                    ->required(fn(callable $get) => $get('jenis_surat') === 'keluar'),

                Forms\Components\Select::make('bulan')
                    ->label('Bulan')
                    ->options([
                        1 => 'Januari',
                        2 => 'Februari',
                        3 => 'Maret',
                        4 => 'April',
                        5 => 'Mei',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'Agustus',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember',
                    ])
                    ->default((int) now()->format('n'))
                    ->visible(fn(callable $get) => $get('jenis_surat') === 'keluar')
                    ->required(fn(callable $get) => $get('jenis_surat') === 'keluar'),

                Forms\Components\TextInput::make('tahun')
                    ->label('Tahun')
                    ->default((int) now()->format('Y'))
                    ->numeric()
                    ->visible(fn(callable $get) => $get('jenis_surat') === 'keluar')
                    ->required(fn(callable $get) => $get('jenis_surat') === 'keluar'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_klafifikasi')->label('Kode Klasifikasi'),
                Tables\Columns\TextColumn::make('nomor_surat')->label('Nomor Surat')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('daftar_pengirim')->label('Pengirim')->sortable(),
                Tables\Columns\TextColumn::make('bulan_roman')
                    ->label('Bulan')
                    ->getStateUsing(fn($record) => $record->bulan_roman)
                    ->sortable(),
                Tables\Columns\TextColumn::make('tahun')->label('Tahun')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('ditujukan_kepada')
                    ->label('Ditujukan Kepada')
                    ->form([
                        Forms\Components\TextInput::make('ditujukan_kepada')->label('Ditujukan Kepada'),
                    ])
                    ->query(fn($query, array $data) => $query->when($data['ditujukan_kepada'] ?? null, fn($q, $v) => $q->where('ditujukan_kepada', 'like', "%{$v}%"))),

                Tables\Filters\SelectFilter::make('jenis_surat')
                    ->label('Jenis Surat')
                    ->options([
                        'masuk' => 'Surat Masuk',
                        'keluar' => 'Surat Keluar',
                    ]),

                Tables\Filters\SelectFilter::make('daftar_pengirim')
                    ->label('Pengirim')
                    ->options([
                        'DPRD' => 'DPRD',
                        'Sekretariat DPRD' => 'Sekretariat DPRD',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurats::route('/'),
            'create' => Pages\CreateSurat::route('/create'),
            'edit' => Pages\EditSurat::route('/{record}/edit'),
        ];
    }
}
