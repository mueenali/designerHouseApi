<?php


use \Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;


function send_email(string $email, Mailable $mail): void
{
    Mail::to($email)->send($mail);
}

