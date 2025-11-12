<?php

namespace App\Filament\Resources\Facultads;

use App\Filament\Resources\Facultads\Pages\CreateFacultad;
use App\Filament\Resources\Facultads\Pages\EditFacultad;
use App\Filament\Resources\Facultads\Pages\ListFacultads;
use App\Filament\Resources\Facultad\Schemas\FacultadForm;
use App\Filament\Resources\Facultads\Tables\FacultadsTable;
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
        return FacultadsTable::configure($table);
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
            'index' => ListFacultads::route('/'),
            'create' => CreateFacultad::route('/create'),
            'edit' => EditFacultad::route('/{record}/edit'),
        ];
    }
}