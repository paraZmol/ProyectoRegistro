<?php

namespace App\Filament\Resources\Tesis;

use App\Filament\Resources\Tesis\Pages\CreateTesis;
use App\Filament\Resources\Tesis\Pages\EditTesis;
use App\Filament\Resources\Tesis\Pages\ListTesis;
use App\Filament\Resources\Tesis\Schemas\TesisForm;
use App\Filament\Resources\Tesis\Tables\TesisTable;
use App\Models\Tesis;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;

class TesisResource extends Resource
{
    protected static ?string $model = Tesis::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::BookOpen;
    protected static ?string $navigationLabel = 'Gestión de Tesis';
    protected static ?string $modelLabel = 'Tesis';
    protected static string|UnitEnum|null $navigationGroup = 'Inventario';
    protected static ?string $recordTitleAttribute = 'titulo';

    public static function form(Schema $schema): Schema
    {
        return TesisForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TesisTable::configure($table);
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
            'index' => ListTesis::route('/'),
            'create' => CreateTesis::route('/create'),
            'edit' => EditTesis::route('/{record}/edit'),
        ];
    }

    // problema N+1
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('item');
    }
}
