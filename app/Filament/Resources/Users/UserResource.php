<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use BackedEnum;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Gestión de Usuarios';
    protected static ?string $modelLabel       = 'Usuario';
    protected static string|UnitEnum|null $navigationGroup = 'Administración';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return UsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit'   => EditUser::route('/{record}/edit'),
        ];
    }

    /** Filtrado del super_admin y del usuario actual */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $userId = Filament::auth()->id(); // ← solución REAL

        return parent::getEloquentQuery()
            ->whereDoesntHave('roles', fn ($q) =>
                $q->where('name', 'super_admin')
            )
            ->when($userId, fn ($q) =>
                $q->where('id', '!=', $userId)
            );
    }
}
