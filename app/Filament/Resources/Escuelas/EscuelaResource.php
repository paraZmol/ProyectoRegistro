<?php

namespace App\Filament\Resources\Escuelas;

use App\Filament\Resources\Escuelas\Pages\CreateEscuela;
use App\Filament\Resources\Escuelas\Pages\EditEscuela;
use App\Filament\Resources\Escuelas\Pages\ListEscuelas;
use App\Filament\Resources\Escuela\Schemas\EscuelaForm;
use App\Filament\Resources\Escuelas\Tables\EscuelasTable;
use App\Models\Escuela;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EscuelaResource extends Resource
{
    protected static ?string $model = Escuela::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;
    protected static ?string $navigationLabel = 'Gestión de Escuelas';
    protected static ?string $modelLabel = 'Escuela';
    protected static string|UnitEnum|null $navigationGroup = 'Académico';
    protected static ?string $recordTitleAttribute = 'escuela';

    public static function form(Schema $schema): Schema
    {
        return EscuelaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EscuelasTable::configure($table);
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
            'index' => ListEscuelas::route('/'),
            'create' => CreateEscuela::route('/create'),
            'edit' => EditEscuela::route('/{record}/edit'),
        ];
    }
}