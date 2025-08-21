<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class LandingPage extends Component
{
    public $name = '';
    public $phone = '';
    public $email = '';
    public $drogueria_name = '';
    public $empleados = '';
    public $mayor_problema = '';
    public $acepta_llamada = false;
    public $acepta_terminos = false;

    public $showThankYou = false;
    public $currentStep = 1;

    protected $rules = [
        'name' => 'required|min:2|max:50',
        'phone' => 'required|regex:/^[0-9+\-\s()]+$/|min:10|max:15',
        'email' => 'nullable|email|max:100',
        'drogueria_name' => 'required|min:2|max:100',
        'empleados' => 'required|in:1,2,3,4+',
        'mayor_problema' => 'required|in:inventario_vencido,conciliaciones,inspecciones,todo',
        'acepta_llamada' => 'required|accepted',
        'acepta_terminos' => 'required|accepted'
    ];

    protected $messages = [
        'name.required' => 'Por favor ingresa tu nombre',
        'name.min' => 'El nombre debe tener al menos 2 caracteres',
        'phone.required' => 'El teléfono es necesario para contactarte',
        'phone.regex' => 'Por favor ingresa un teléfono válido',
        'email.email' => 'Ingresa un email válido (opcional)',
        'drogueria_name.required' => 'Necesitamos el nombre de tu droguería',
        'empleados.required' => 'Selecciona cuántos empleados tienes',
        'mayor_problema.required' => 'Ayúdanos a entender tu principal problema',
        'acepta_llamada.accepted' => 'Necesitamos tu autorización para contactarte',
        'acepta_terminos.accepted' => 'Debes aceptar los términos para continuar'
    ];

    public function nextStep()
    {
        if ($this->currentStep == 1) {
            $this->validate([
                'name' => $this->rules['name'],
                'phone' => $this->rules['phone'],
                'drogueria_name' => $this->rules['drogueria_name']
            ]);
            $this->currentStep = 2;
        }
    }

    public function prevStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function submitLead()
    {
        $this->validate();

        try {
            // Guardar lead en base de datos
            $leadData = [
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email ?: null,
                'drogueria_name' => $this->drogueria_name,
                'empleados' => $this->empleados,
                'mayor_problema' => $this->mayor_problema,
                'source' => 'landing_page_droguerias',
                'created_at' => now(),
                'utm_source' => request('utm_source'),
                'utm_medium' => request('utm_medium'),
                'utm_campaign' => request('utm_campaign')
            ];

            // Aquí integrarías con tu sistema de CRM
            // Lead::create($leadData);

            // Enviar notificación por email
            // Mail::to('ventas@tuapp.com')->send(new NuevoLeadDrogueria($leadData));

            // Enviar WhatsApp al lead (opcional)
            $this->sendWhatsAppNotification();

            $this->showThankYou = true;
            $this->reset(['name', 'phone', 'email', 'drogueria_name', 'empleados', 'mayor_problema']);

            // Tracking de conversión
            $this->dispatch('conversion-completed', [
                'event' => 'lead_generated_drogueria',
                'value' => 150, // Valor estimado del lead
                'problema' => $this->mayor_problema
            ]);
        } catch (\Exception $e) {
            Log::error('Error al procesar lead: ' . $e->getMessage());
            session()->flash('error', 'Hubo un problema. Por favor intenta de nuevo o llámanos al WhatsApp.');
        }
    }

    public function sendWhatsAppNotification()
    {
        // Mensaje personalizado basado en el problema principal
        $problemas = [
            'inventario_vencido' => 'productos vencidos',
            'conciliaciones' => 'conciliaciones manuales',
            'inspecciones' => 'inspecciones sanitarias',
            'todo' => 'múltiples problemas operativos'
        ];

        $problema = $problemas[$this->mayor_problema] ?? 'gestión de droguería';

        $message = "¡Hola {$this->name}! 👋\n\n";
        $message .= "Gracias por tu interés en nuestro software para {$this->drogueria_name}.\n\n";
        $message .= "Vi que tu principal problema es: *{$problema}*\n\n";
        $message .= "Te contactaré en las próximas 2 horas para mostrarte cómo podemos solucionarlo específicamente.\n\n";
        $message .= "Mientras tanto, aquí tienes un video de 3 minutos: [LINK_VIDEO]\n\n";
        $message .= "¡Hablamos pronto! 🚀";

        // Aquí integrarías con WhatsApp Business API
        // WhatsAppService::send($this->phone, $message);
    }

    public function resetForm()
    {
        $this->showThankYou = false;
        $this->currentStep = 1;
        $this->reset();
    }

    public function render()
    {
        return view('livewire.landing-page')
            ->layout('components.layouts.app', [
                'title' => 'Bienvenido a nuestra Droguería',
                'description' => 'Descubre cómo nuestro software puede transformar tu droguería'
            ]);
    }
}
