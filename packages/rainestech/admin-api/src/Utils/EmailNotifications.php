<?php

namespace Rainestech\AdminApi\Utils;

use Mail;
use Rainestech\AdminApi\Entity\MailTemplates;
use Rainestech\AdminApi\Entity\Users;

class EmailNotifications
{
    public function sendOTP(Users $user, $tokens, $subject)
    {
        $name = $user->firstName == $user->lastName ? $user->firstName : $user->lastName . ' ' . $user->firstName;
        $temp = MailTemplates::where('name', 'OTP')->first();
        if ($temp) {
            $message = $temp->template;
            $messag = str_replace('{{username}}', strtoupper($name), $message);
            $messa = str_replace('{{token}}', $tokens, $messag);
            Mail::send('email', ['template' => $messa], function ($mail) use ($temp, $user, $subject, $name) {
                $mail->to($user->email, $name);
                $mail->subject($subject);
            });
        }

        return $this;
    }

    public function testMail()
    {
        $temp = MailTemplates::find(1);
        if ($temp) {
            $message = $temp->template;
            Mail::send('email', ['template' => $message], function ($mail) {
                $mail->to('ayoola@rainestech.com');
                $mail->subject('Tritek Test Mail');
            });
        }

        return $this;
    }

    public function sendWelcome(Users $user)
    {
        $temp = MailTemplates::where('name', 'welcome')->first();
        if ($temp) {
            $message = $temp->template;
            $msg = str_replace('{{username}}', strtoupper($user->user_nicename), $message);
            Mail::send('email', ['template' => $msg], function ($mail) use ($temp, $user) {
                $mail->to($user->user_email, $user->user_nicename);
                $mail->subject('Welcome to Tritek Consulting');
            });
        }

        return $this;
    }

    public function sendPasswordChange(Users $user)
    {
        $temp = MailTemplates::where('name', 'password')->first();
        if ($temp) {
            $message = $temp->template;
            $msg = str_replace('{{username}}', strtoupper($user->user_nicename), $message);
            Mail::send('email', ['template' => $msg], function ($mail) use ($temp, $user) {
                $mail->to($user->user_email, $user->user_nicename);
                $mail->subject('Tritek Careers Password Change');
            });
        }

        return $this;
    }
}
