<?php

namespace App\Filament\Resources\Estudiantes;

use App\Filament\Resources\Estudiantes\Pages\CreateEstudiante;
use App\Filament\Resources\Estudiantes\Pages\EditEstudiante;
use App\Filament\Resources\Estudiantes\Pages\ListEstudiantes;
use App\Filament\Resources\Estudiante\Schemas\EstudianteForm;
use App\Filament\Resources\Estudiantes\Tables\EstudiantesTable;
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
        return EstudiantesTable::configure($table);
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
            'index' => ListEstudiantes::route('/'),
            'create' => CreateEstudiante::route('/create'),
            'edit' => EditEstudiante::route('/{record}/edit'),
        ];
    }
}
