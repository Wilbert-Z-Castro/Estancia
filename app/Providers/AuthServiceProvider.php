<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;
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
            // Continuar con el contenido del correo
            $mail->line('Gracias por registrarte en nuestra aplicación. Para completar el proceso de registro, por favor haz clic en el botón de abajo para verificar tu dirección de correo electrónico.')
                ->line('Tu correo electrónico es: ' . $notifiable->email) // Incluir el correo electrónico
                ->action('Verificar mi correo', $url)
                ->line('Si no creaste una cuenta, no te preocupes, simplemente ignora este correo.');

            // Condicional para el rol 'DirCarrera'
            if ($notifiable->rol == 'DirCarrera') {
                $mail->line('');
            }

            // Continuar con el resto del mensaje
            $mail->line('Si tienes problemas para hacer clic en el botón "Verificar mi correo", copia y pega la siguiente URL en tu navegador:')
                ->line($url)
                ->salutation('Saludos,');

            return $mail;
        });
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            // Generar la URL de restablecimiento usando la ruta 'password.reset'
            $url = url(route('password.reset', ['token' => $token, 'email' => $notifiable->getEmailForPasswordReset()], false));
        
            $mail = new MailMessage;
            $mail->subject('Solicitud de restablecimiento de contraseña')
                 ->greeting('Hola,')
                 ->line('Recibiste este correo porque se solicitó un restablecimiento de contraseña para tu cuenta.')
                 ->action('Restablecer Contraseña', $url)
                 ->line('Este enlace de restablecimiento de contraseña caducará en 60 minutos.')
                 ->line('Si no solicitaste un restablecimiento de contraseña, no necesitas realizar ninguna acción.')
                 ->salutation('Saludos,');
            return $mail;
        });
    }
}
