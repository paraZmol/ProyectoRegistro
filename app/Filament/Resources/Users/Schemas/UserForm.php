<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Filament\Schemas\Components\Section;

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
                            ->required(fn ($operation) => $operation === 'create')
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->confirmed()
                            ->maxLength(255),

                        TextInput::make('password_confirmation')
                            ->label('Confirmar Contraseña')
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->visible(fn (string $operation): bool => $operation === 'create')
                            ->required(fn (string $operation): bool => $operation === 'create'),
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
