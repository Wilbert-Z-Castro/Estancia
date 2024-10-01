<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            $mail = new MailMessage;
            
            // Agregar logo personalizado
            $mail->line('<img src="' . asset('img/UPE_vertical.jpg') . '" alt="Logo" style="max-width: 100%; height: auto;"/>');
            
            // Continuar con el contenido del correo
            $mail->line('Gracias por registrarte en nuestra aplicación. Para completar el proceso de registro, por favor haz clic en el botón de abajo para verificar tu dirección de correo electrónico.')
                ->line('Tu correo electrónico es: ' . $notifiable->email) // Incluir el correo electrónico
                ->action('Verificar mi correo', $url)
                ->line('Si no creaste una cuenta, no te preocupes, simplemente ignora este correo.')
                ->line('Si tienes problemas para hacer clic en el botón "Verificar mi correo", copia y pega la siguiente URL en tu navegador:')
                ->line($url)
                ->salutation('Saludos,');
        
            return $mail;
        });
    }
}
