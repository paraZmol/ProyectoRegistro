<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([

                Section::make('Datos del Usuario')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombres y Apellidos')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                    ])->columns(2),

                Section::make('Contraseña')
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            // 1. REGLA: Obligatorio solo si estamos CREANDO el usuario
                            ->required(fn (string $operation): bool => $operation === 'create')

                            // 2. REGLA: Solo enviar a la BD si el usuario escribió algo
                            // (Esto permite dejarlo vacío para no cambiar la contraseña)
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->confirmed()
                            ->maxLength(255),

                        TextInput::make('password_confirmation')
                            ->label('Confirmar Contraseña')
                            ->password()
                            ->revealable()
                            // 4. REGLA: Misma lógica, obligatorio solo al crear
                            ->required(fn (string $operation): bool => $operation === 'create')
                            // 5. Visible SIEMPRE (quitamos la condición de visibility)
                            ->dehydrated(false),
                    ])->columns(2),

                // slector de rol
                Section::make('Asignación de Rol')
                    ->schema([
                        Select::make('roles')
                            ->relationship(
                                name: 'roles',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->where('name', '!=', 'super_admin')
                            )
                            ->multiple()
                            ->maxItems(1)
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Rol del Usuario'),
                    ]),
            ]);
    }
}
