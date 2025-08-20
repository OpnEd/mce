<?php

namespace App\Livewire;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Component;

class TermsAndConditions extends Component
{
    /* public bool $accepts_terms = false;

    public bool $accepts_policy = false;

    public function render()
    {
        $policyPath = resource_path('views/markdown/policy.md');
        $termsPath = resource_path('views/markdown/terms.md');

        if (File::exists($policyPath)) {
            $policy = Str::markdown(File::get($policyPath));
        } else {
            $policy = 'No se encontró el archivo de política de calidad.';
        }

        if (File::exists($termsPath)) {
            $terms = Str::markdown(File::get($termsPath));
        } else {
            $terms = 'No se encontró el archivo de términos y condiciones.';
        }

        return view('livewire.terms-and-conditions', [
            'policy' => $policy,
            'terms' => $terms,
        ]);
    } */
}
