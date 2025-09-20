<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratResource\Pages;
use App\Filament\Resources\SuratResource\RelationManagers;
use App\Models\Surat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SuratResource extends Resource
{
    protected static ?string $model = Surat::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'Surat'; // Tambahkan ini
    protected static ?string $pluralModelLabel = 'Surat'; // Tambahkan ini
    protected static ?string $modelLabel = 'Surat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nomor_surat')
                    ->label('Nomor Surat')
                    ->required()
                    ->unique(),
                Forms\Components\TextInput::make('perihal')
                    ->label('Perihal')
                    ->required(),
                Forms\Components\Select::make('tujuan_id')
                    ->label('Tujuan')
                    ->relationship('tujuan', 'nama')
                    ->required(),
                Forms\Components\TextInput::make('ditujukan_kepada')
                    ->label('Ditujukan Kepada')
                    ->required(),
                Forms\Components\Select::make('jenis_surat')
                    ->label('Jenis Surat')
                    ->options([
                        'masuk' => 'Surat Masuk',
                        'keluar' => 'Surat Keluar',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_surat')
                    ->label('Tanggal Surat')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_surat')->label('Nomor Surat')->searchable(),
                Tables\Columns\TextColumn::make('perihal')->label('Perihal')->searchable(),
                Tables\Columns\TextColumn::make('tujuan.nama')->label('Tujuan')->searchable(),
                Tables\Columns\TextColumn::make('ditujukan_kepada')->label('Ditujukan Kepada')->searchable(),
                Tables\Columns\TextColumn::make('jenis_surat')->label('Jenis Surat'),
                Tables\Columns\TextColumn::make('tanggal_surat')->label('Tanggal Surat')->date(),
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
        return [
            //
        ];
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
