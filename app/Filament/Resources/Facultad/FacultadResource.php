<?php

namespace App\Filament\Resources\Facultad;

use App\Filament\Resources\Facultad\Pages\CreateFacultad;
use App\Filament\Resources\Facultad\Pages\EditFacultad;
use App\Filament\Resources\Facultad\Pages\ListFacultad;
use App\Filament\Resources\Facultad\Schemas\FacultadForm;
use App\Filament\Resources\Facultad\Tables\FacultadTable;
use App\Models\Facultad;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class FacultadResource extends Resource
{
    protected static ?string $model = Facultad::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingLibrary;
    protected static ?string $navigationLabel = 'Gestion de Facultades';
    protected static ?string $modelLabel = 'Facultad';

    protected static ?string $recordTitleAttribute = 'facultad';
    protected static string|UnitEnum|null $navigationGroup = 'Académico';

    public static function form(Schema $schema): Schema
    {
        return FacultadForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FacultadTable::configure($table);
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
            'index' => ListFacultad::route('/'),
            'create' => CreateFacultad::route('/create'),
            'edit' => EditFacultad::route('/{record}/edit'),
        ];
    }
}
