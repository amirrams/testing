<?php

namespace Docfav\Application\Handlers;

use DateTime;
use Docfav\Domain\Events\UserRegisteredEvent;

class SendWelcomeEmailHandler
{

    /**
     * Simulación del envío de un email de bienvenida.
     *
     * @param UserRegisteredEvent $event Evento de usuario registrado.
     */
    public function __invoke(UserRegisteredEvent $event): void
    {
        // Mensaje de salida en consola
        /* echo sprintf(
            "Welcome email sent to user with ID: %s and email: %s\n\n",
            $event->getUserId(),
            $event->getEmail()
        ); */

        // Registrar el log
        $this->writeLog(sprintf(
            "Welcome email sent to user with ID: %s and email: %s",
            $event->getUserId(),
            $event->getEmail()
        ));
    }

    /**
     * Escribe un mensaje en el archivo de log.
     *
     * @param string $message Mensaje a registrar.
     * @param string $level Nivel del log (INFO, ERROR, etc.).
     */
    private function writeLog(string $message, string $level = 'INFO'): void
    {
        $logFile = PROJECT_ROOT . '/logs/app.log';
        if (!is_dir(PROJECT_ROOT . '/logs')) {
            mkdir(PROJECT_ROOT . '/logs', 0777, true);
        }
        $logMessage = sprintf(
            "[%s] [%s]: %s\n",
            (new DateTime())->format('Y-m-d H:i:s'),
            $level,
            $message
        );
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

}
