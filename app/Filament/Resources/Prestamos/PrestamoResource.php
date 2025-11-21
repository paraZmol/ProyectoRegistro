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
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\Action;

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
        // obtenemos la consulta base
        $query = parent::getEloquentQuery()
            ->with(['estudiante', 'item.tablet', 'item.tesis']);

        // obtenemos al usuario actual
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Si no hay usuario logueado (raro, pero posible), devolvemos la consulta tal cual
        if (!$user) {
            return $query;
        }

        // aplicamos el filtro de seguridad según el Rol

        // Encargado de TABLET
        if ($user->hasRole('Encargado de Tablet')) {
            // Solo traemos préstamos cuyo item sea tipo 'Tablet'
            $query->whereHas('item', function (Builder $q) {
                $q->where('tipo', 'Tablet');
            });
        }

        // Encargado de TESIS
        if ($user->hasRole('Encargado de Tesis')) {
            // Solo traemos préstamos cuyo item sea tipo 'Tesis'
            $query->whereHas('item', function (Builder $q) {
                $q->where('tipo', 'Tesis');
            });
        }

        return $query;
    }
}
