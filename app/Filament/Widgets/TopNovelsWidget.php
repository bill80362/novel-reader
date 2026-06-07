<?php

namespace App\Filament\Widgets;

use App\Models\Novel;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class TopNovelsWidget extends TableWidget
{
    protected static ?string $heading = 'Top 10 小說（依瀏覽數）';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Novel::query()->orderByDesc('view_count')->limit(10),
            )
            ->columns([
                TextColumn::make('title')
                    ->label('標題'),
                TextColumn::make('category.name')
                    ->label('分類'),
                TextColumn::make('status')
                    ->label('狀態'),
                TextColumn::make('view_count')
                    ->label('瀏覽數')
                    ->numeric()
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
