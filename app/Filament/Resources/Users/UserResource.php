<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum; // <-- IMPORTANTE

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    // ----- AQUÍ ESTÁN LOS TIPOS CORRECTOS -----

    // Icono (línea 22) SÓLO ACEPTA ?string
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Gestión de Usuarios';
    protected static ?string $modelLabel = 'Usuario';

    // Grupo (línea 26) SÍ ACEPTA UnitEnum
    protected static string|UnitEnum|null $navigationGroup = 'Administración';

    // -------------------------------------------

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    // ----- LÓGICA PARA OCULTAR AL SUPER ADMIN -----
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'super_admin');
            })
            ->where('id', '!=', auth()->id());
    }
}