<?php

namespace App\Filament\Resources\Tablet;

use App\Filament\Resources\Tablet\Pages\CreateTablet;
use App\Filament\Resources\Tablet\Pages\EditTablet;
use App\Filament\Resources\Tablet\Pages\ListTablet;
use App\Filament\Resources\Tablet\Schemas\TabletForm;
use App\Filament\Resources\Tablet\Tables\TabletTable;
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
        return TabletTable::configure($table);
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
            'index' => ListTablet::route('/'),
            'create' => CreateTablet::route('/create'),
            'edit' => EditTablet::route('/{record}/edit'),
        ];
    }

    /* ---  Eager Loading ---
    * problema N+1, ya que ara la consulta principal y 50+ para relacionar las tablets con los items
    * entonces hacemos un consulta extra donde relaciona el item con tablet
    * asi que en ves de hacer 50 + 1 consultas, solo hacemos 2 consultas*/
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('item');
    }
}
