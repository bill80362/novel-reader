<?php

namespace App\Filament\Resources\Novels\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NovelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set(
                        'slug',
                        Str::slug($state ?? ''),
                    )),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                Textarea::make('description')
                    ->columnSpanFull(),
                FileUpload::make('cover_image')
                    ->image()
                    ->visibility('public'),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Select::make('status')
                    ->options([
                        'draft' => '草稿',
                        'published' => '已發佈',
                        'completed' => '已完結',
                    ])
                    ->required()
                    ->default('draft'),
                Toggle::make('is_featured')
                    ->label('精選推薦'),
                Fieldset::make('SEO 設定')
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('SEO 標題')
                            ->maxLength(60),
                        TextInput::make('seo_description')
                            ->label('SEO 描述')
                            ->maxLength(160),
                        TextInput::make('seo_keywords')
                            ->label('SEO 關鍵字')
                            ->maxLength(255),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
