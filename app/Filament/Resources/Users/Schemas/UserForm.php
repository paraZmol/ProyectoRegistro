<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Section;   // <-- Este es el que te da error
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

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
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->maxLength(255)
                            ->confirmed(fn (string $operation): bool => $operation === 'create'),

                        TextInput::make('password_confirmation')
                            ->label('Confirmar Contraseña')
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->visible(fn (string $operation): bool => $operation === 'create')
                            ->required(fn (string $operation): bool => $operation === 'create'),
                    ])->columns(2),

                // 5. ¡El campo clave! El selector de Rol
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
