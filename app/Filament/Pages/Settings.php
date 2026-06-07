<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.settings';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'ga4_id' => Setting::get('ga4_id'),
            'site_name' => Setting::get('site_name'),
            'social_line' => Setting::get('social_line'),
            'social_twitter' => Setting::get('social_twitter'),
            'social_facebook' => Setting::get('social_facebook'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    TextInput::make('ga4_id')
                        ->label('GA4 Measurement ID')
                        ->placeholder('G-XXXXXXXXXX'),
                    TextInput::make('site_name')
                        ->label('網站名稱')
                        ->required(),
                    TextInput::make('social_line')
                        ->label('LINE 連結')
                        ->url(),
                    TextInput::make('social_twitter')
                        ->label('Twitter / X 連結')
                        ->url(),
                    TextInput::make('social_facebook')
                        ->label('Facebook 連結')
                        ->url(),
                ])
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('儲存設定')
                                ->submit('save'),
                        ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        Notification::make()
            ->title('設定已儲存')
            ->success()
            ->send();
    }
}
