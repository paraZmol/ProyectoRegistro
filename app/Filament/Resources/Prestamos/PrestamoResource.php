<?php

namespace App\Filament\Resources\Prestamos;

use App\Filament\Resources\Prestamos\Pages\CreatePrestamo;
use App\Filament\Resources\Prestamos\Pages\EditPrestamo;
use App\Filament\Resources\Prestamos\Pages\ListPrestamos;
use App\Filament\Resources\Prestamos\Schemas\PrestamoForm;
use App\Filament\Resources\Prestamos\Tables\PrestamosTable;
use App\Models\Prestamo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Form;

class PrestamoResource extends Resource
{
    protected static ?string $model = Prestamo::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::RectangleStack;
    protected static ?string $navigationLabel = 'Gestión de Préstamos';
    protected static ?string $modelLabel = 'Préstamo';
    protected static string|UnitEnum|null $navigationGroup = 'Préstamos';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        // Lo dejaremos así por ahora, el formulario de creación
        // será una página personalizada.
        return PrestamoForm::configure($schema);
        //return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return PrestamosTable::configure($table);
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
            'index' => ListPrestamos::route('/'),
            'create' => CreatePrestamo::route('/create'),
            'edit' => EditPrestamo::route('/{record}/edit'),
        ];
    }

    // problema N+1
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['estudiante', 'item']);
    }
}
