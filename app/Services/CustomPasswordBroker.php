<?php

namespace App\Services;

use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class CustomPasswordBroker extends PasswordBroker implements PasswordBrokerContract
{
    /**
     * Send a password reset link to a user.
     *
     * @param  array  $credentials
     * @return string
     */
    public function sendResetLink(array $credentials)
    {
        // First we will check to see if we found a user at the given credentials and
        // if we did not we will redirect back to this current URI with a piece of
        // "flash" data in the session to indicate to the developers the errors.
        $user = $this->getUser($credentials);

        if (is_null($user)) {
            return static::INVALID_USER;
        }

        // Once we have the reset token, we are ready to send the message out to this
        // user with a link to reset their password. We will then redirect back to
        // the current URI having nothing set in the session to indicate errors.
        $token = $this->tokens->create($user);
        
        // In development/local environment, don't try to send email
        if (app()->environment('local', 'development')) {
            // Log the token for development use
            \Illuminate\Support\Facades\Log::info('DEV: Password reset token created', [
                'email' => $user->getEmailForPasswordReset(),
                'token' => $token,
                'user_id' => $user->id
            ]);
            
            return static::RESET_LINK_SENT;
        }
        
        // Only send email in production
        $user->sendPasswordResetNotification($token);

        return static::RESET_LINK_SENT;
    }
}