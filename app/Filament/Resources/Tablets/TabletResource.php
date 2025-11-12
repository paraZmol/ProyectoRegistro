<?php

namespace App\Filament\Resources\Tablets;

use App\Filament\Resources\Tablets\Pages\CreateTablet;
use App\Filament\Resources\Tablets\Pages\EditTablet;
use App\Filament\Resources\Tablets\Pages\ListTablets;
use App\Filament\Resources\Tablet\Schemas\TabletForm;
use App\Filament\Resources\Tablets\Tables\TabletsTable;
use App\Models\Tablet;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;

class TabletResource extends Resource
{
    protected static ?string $model = Tablet::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::DeviceTablet;
    protected static ?string $navigationLabel = 'Gestión de Tablets';
    protected static ?string $modelLabel = 'Tablet';
    protected static string|UnitEnum|null $navigationGroup = 'Inventario';
    protected static ?string $recordTitleAttribute = 'codigo';

    public static function form(Schema $schema): Schema
    {
        return TabletForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TabletsTable::configure($table);
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
            'index' => ListTablets::route('/'),
            'create' => CreateTablet::route('/create'),
            'edit' => EditTablet::route('/{record}/edit'),
        ];
    }

    /* ---  Eager Loading ---
    * Carga la relación 'item' para que 'item.estado_disponibilidad'
    * (la columna 'Estado') funcione en el listado de la tabla.*/
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('item');
    }
}
