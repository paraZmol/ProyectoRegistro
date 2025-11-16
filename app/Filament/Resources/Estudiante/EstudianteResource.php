<?php

namespace App\Filament\Resources\Estudiante;

use App\Filament\Resources\Estudiante\Pages\CreateEstudiante;
use App\Filament\Resources\Estudiante\Pages\EditEstudiante;
use App\Filament\Resources\Estudiante\Pages\ListEstudiante;
use App\Filament\Resources\Estudiante\Schemas\EstudianteForm;
use App\Filament\Resources\Estudiante\Tables\EstudianteTable;
use App\Models\Estudiante;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EstudianteResource extends Resource
{
    protected static ?string $model = Estudiante::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;
    protected static ?string $navigationLabel = 'Gestión de Estudiantes';
    protected static ?string $modelLabel = 'Estudiante';
    protected static string|UnitEnum|null $navigationGroup = 'Académico';

    protected static ?string $recordTitleAttribute = 'apellidos';

    public static function form(Schema $schema): Schema
    {
        return EstudianteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EstudianteTable::configure($table);
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
            'index' => ListEstudiante::route('/'),
            'create' => CreateEstudiante::route('/create'),
            'edit' => EditEstudiante::route('/{record}/edit'),
        ];
    }
}
