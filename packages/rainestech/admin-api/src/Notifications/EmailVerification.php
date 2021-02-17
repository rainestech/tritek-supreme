<?php

namespace Rainestech\AdminApi\Notifications;

use Mail;
use Rainestech\AdminApi\Entity\MailTemplates;
use Rainestech\AdminApi\Entity\Users;

class EmailVerification
{
    public function sendVerification(Users $user)
    {
        $user->lastPwd = $this->generatePin();
        $user->save();

        $temp = MailTemplates::where('name', 'Email Verification')->first();
        if ($temp) {
            $message = $temp->template;
            $messag = str_replace('{{username}}', strtoupper($user->username), $message);
            $messa = str_replace('{{token}}', $user->lastPwd, $messag);
            Mail::send('notification', ['template' => $messa], function ($mail) use ($temp, $user) {
                $mail->to($user->email, $user->username);
                $mail->subject('Tritek Careers - Email Verification');
            });

            return $this;
        }

        abort(422, 'Template Not Found');
    }

    public function resetPassword(Users $user)
    {
        $user->lastPwd = $this->generatePin();
        $user->save();

        $temp = MailTemplates::where('name', 'Email Verification')->first();
        if ($temp) {
            $message = $temp->template;
            $messag = str_replace('{{username}}', strtoupper($user->username), $message);
            $messa = str_replace('{{token}}', $user->lastPwd, $messag);
            Mail::send('notification', ['template' => $messa], function ($mail) use ($temp, $user) {
                $mail->to($user->email, $user->username);
                $mail->subject('Tritek Careers - Reset Password OTP');
            });

            return $this;
        }

        abort(422, 'Template Not Found');
    }

    /**
     * @param int $digits
     * @return string
     */
    private function generatePin($digits = 6)
    {
        $i = 0;
        $pin = "";

        while ($i < $digits) {
            $pin .= '' . mt_rand(0, 9);
            $i++;
        }

        return $pin;
    }
}
