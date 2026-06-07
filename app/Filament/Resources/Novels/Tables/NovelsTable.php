<?php

namespace App\Filament\Resources\Novels\Tables;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class NovelsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('封面'),
                TextColumn::make('title')
                    ->label('標題')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('分類')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('狀態')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'completed' => 'info',
                    }),
                IconColumn::make('is_featured')
                    ->label('精選')
                    ->boolean(),
                TextColumn::make('view_count')
                    ->label('瀏覽數')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('更新時間')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => '草稿',
                        'published' => '已發佈',
                        'completed' => '已完結',
                    ]),
                SelectFilter::make('category')
                    ->relationship('category', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('publish')
                        ->label('批次發佈')
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'published']))
                        ->requiresConfirmation(),
                    BulkAction::make('draft')
                        ->label('批次下架')
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'draft']))
                        ->requiresConfirmation(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
