<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TujuanResource\Pages;
use App\Models\Tujuan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TujuanResource extends Resource
{
    protected static ?string $model = Tujuan::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox'; // Icon lebih relevan
    protected static ?string $navigationLabel = 'Tujuan Surat';
    protected static ?string $pluralModelLabel = 'Tujuan Surat';
    protected static ?string $modelLabel = 'Tujuan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Tujuan')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Tujuan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
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
            'index' => Pages\ListTujuans::route('/'),
            'create' => Pages\CreateTujuan::route('/create'),
            'edit' => Pages\EditTujuan::route('/{record}/edit'),
        ];
    }
}