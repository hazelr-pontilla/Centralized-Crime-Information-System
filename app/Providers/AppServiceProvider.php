<?php

namespace App\Providers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\ServiceProvider;
use URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (env(key: 'APP_ENV') !=='local') {
            URL::forceScheme(scheme:'https');
          }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // $this->app['url']->forceRootUrl(env('NGROK_URL'));

        // TextInput::configureUsing(function (TextInput $textInput) {
        //     $textInput->inlineLabel();
        // });

        // Radio::configureUsing(function (Radio $radio) {
        //     $radio->inlineLabel();
        // });

        // Select::configureUsing(function (Select $select) {
        //     $select->inlineLabel();
        // });

        // DatePicker::configureUsing(function (DatePicker $datePicker) {
        //     $datePicker->inlineLabel();
        // });

        // Section::configureUsing(function (Section $section) {
        //     $section
        //     ->columns()
        //     ->compact();
        // });

    }
}
