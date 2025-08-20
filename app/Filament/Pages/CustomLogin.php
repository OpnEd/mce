<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login;

class CustomLogin extends Login
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
                ViewField::make('termsAndConditions')->view('livewire.terms-and-conditions'),
            ]);
    }

    public function authenticate(): ?LoginResponse
    {
        $termsAndConditions = $this->form->getState()['termsAndConditions'];
        if (empty($termsAndConditions)) {
            Notification::make()
                ->title('Términos y condiciones')
                ->body('Debes aceptar nuestros Términos y Condiciones para continuar.')
                ->danger()
                ->send();
            return null;
        }
        return parent::authenticate();
    }
}
