<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required(),
                Forms\Components\Textarea::make('text')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('category_id')
                    ->native(false)
                    ->createOptionAction(fn ($action) => $action->slideOver())
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Name')
                            ->required(),
                    ])
                    ->relationship('category', 'name'),
                Repeater::make('comments')
                        ->columnSpanFull()
                        ->defaultItems(1)
                        ->extraItemActions([
                            Action::make('publish')
                                ->requiresConfirmation()
                                ->color('success')
                                ->button()
                                ->label('Publish')
                                ->form([
                                    TextInput::make('text')
                                        ->label('Text')
                                        ->required(),
                                    Select::make('category_id')
                                        ->relationship('category', 'name')
                                        ->createOptionAction(fn ($action) => $action->slideOver())
                                        ->createOptionForm([
                                            TextInput::make('name')
                                                ->label('Name')
                                                ->required(),
                                        ])
                                ])
                                ->action(fn ($record) => info('Published comment')),
                        ])
                        ->deleteAction(fn ($action) => $action->requiresConfirmation())
                        ->relationship('comments')
                        ->schema([
                            Textarea::make('text')->required()
                        ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListPosts::route('/'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
