<?php

namespace App\Filament\Resources\Chapters\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ChapterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('novel_id')
                    ->relationship('novel', 'title')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('chapter_number')
                    ->required()
                    ->numeric(),
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set(
                        'slug',
                        Str::slug($state ?? ''),
                    )),
                TextInput::make('slug')
                    ->required(),
                RichEditor::make('content')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('word_count')
                    ->label('字數（自動計算）')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false),
                DateTimePicker::make('published_at')
                    ->label('發佈時間'),
            ]);
    }
}
