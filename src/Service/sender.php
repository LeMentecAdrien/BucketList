<?php

namespace App\Service;



use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class sender
{
    public function __construct(private MailerInterface $mailer){

    }


    public function sendEmail(string $subject, string $text, string $dest){
        $email = new Email();
        $email->subject($subject)
            ->text($text)
            ->from('no-reply@serie.com')
            ->to($dest);

        $this->mailer->send($email);
    }
}