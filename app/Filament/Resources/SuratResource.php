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
                Forms\Components\TextInput::make('kode_klafifikasi')
                    ->label('Kode Klasifikasi')
                    ->maxLength(50)
                    ->required(fn(callable $get) => $get('jenis_surat') === 'keluar')
                    ->visible(fn(callable $get) => $get('jenis_surat') === 'keluar'),

                Forms\Components\TextInput::make('nomor_surat')
                    ->label('Nomor Surat')
                    ->default(fn() => Surat::generateNextNumber())
                    ->disabled()
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\Select::make('daftar_pengirim')
                    ->label('Daftar Pengirim')
                    ->options([
                        'DPRD' => 'DPRD',
                        'Sekretariat DPRD' => 'Sekretariat DPRD',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('ditujukan_kepada')
                    ->label('Ditujukan Kepada')
                    ->required()
                    ->required(fn(callable $get) => $get('jenis_surat') === 'masuk')
                    ->visible(fn(callable $get) => $get('jenis_surat') === 'masuk'),

                Forms\Components\Select::make('jenis_surat')
                    ->label('Jenis Surat')
                    ->options([
                        'masuk' => 'Surat Masuk',
                        'keluar' => 'Surat Keluar',
                    ])
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(fn($state, $set) => $state !== 'masuk' ? $set('perihal', null) : null),
                Forms\Components\TextInput::make('perihal')
                    ->label('Perihal')
                    ->required(fn(callable $get) => $get('jenis_surat') === 'masuk')
                    ->visible(fn(callable $get) => $get('jenis_surat') === 'masuk'),

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
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_klafifikasi')->label('Kode Klasifikasi'),
                Tables\Columns\TextColumn::make('nomor_surat')->label('Nomor Surat')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('daftar_pengirim')->label('Pengirim')->sortable(),
                Tables\Columns\TextColumn::make('ditujukan_kepada')->label('Ditujukan Kepada')->searchable(),

                // tampilkan bulan sebagai Romawi via accessor bulan_roman
                Tables\Columns\TextColumn::make('bulan_roman')
                    ->label('Bulan')
                    ->getStateUsing(fn($record) => $record->bulan_roman)
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tujuan_id')
                    ->label('Tujuan')
                    ->relationship('tujuan', 'nama'),
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
