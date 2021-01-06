<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable {

    use Queueable,
        SerializesModels;

    public $data = null;
    public $type = '';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $type_mail) {
        $this->data = $data;
        $this->type = $type_mail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        switch ($this->type) {
            case 'verify-email':
                return $this->verify_email();
                break;
            case 'change-password':
                return $this->change_password();
                break;
            case 'forgot-password':
                return $this->forgotPassword();
                break;
        }
    }

    private function verify_email() {
        return $this->from(env('MAIL_FROM_ADDRESS'))
                        ->markdown('emails.mail-confirm-markdown')
                        ->with([
                            'user' => $this->data,
                            'url' => route('verify-email', ['code' => base64_encode($this->data->email)."--".$this->data->activation_code]),
                            'company' => env('APP_COMPANY_NAME')
        ]);
    }
    
    private function change_password() {
        return $this->from(env('MAIL_FROM_ADDRESS'))
                        ->markdown('emails.change-password-markdown')
                        ->with([
                            'user' => $this->data,
                            'company' => env('APP_COMPANY_NAME')
        ]);
    }

    private function forgotPassword(){
        return $this->from(env('MAIL_FROM_ADDRESS'))
                        ->markdown('emails.forgot-password-markdown')
                        ->with([
                            'user' => $this->data,
                            'url' => route('reset-password', ['code' => base64_encode($this->data->email)."--".$this->data->activation_code]),
                            'company' => env('APP_COMPANY_NAME')
        ]);
    }
}
