<?php

namespace App\Mail;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    private $model;

    /**
     * Create a new message instance.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $access_payload = [ 'iss' => "bearer",  'sub' => $this->model->id, 'iat' => time(), 'exp' => time() + 60*120 ];
        $access_token = JWT::encode($access_payload, $this->model->email);

        return $this
            ->subject(trans('Pasar Negeri'))
            ->from(env('MAIL_USERNAME'), 'Pasar Negeri')
            ->view('emails.forgot-password')
            ->with(['email' => $this->model->email, 'id' => $this->model->id, 'token' => $access_token]);
    }
}