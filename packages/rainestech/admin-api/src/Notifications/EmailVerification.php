<?php

namespace Rainestech\AdminApi\Notifications;

use Mail;
use Rainestech\AdminApi\Entity\ContactMessages;
use Rainestech\AdminApi\Entity\MailTemplates;
use Rainestech\AdminApi\Entity\Users;

class EmailVerification
{
    public function sendVerification(Users $user)
    {
        $user->lastPwd = $this->generatePin();
        $user->save();

        $name = $user->companyName ? $user->companyName : $user->name;

        $temp = MailTemplates::where('name', 'OTP_REG')->first();
        if ($temp) {
            $message = $temp->template;
            $messag = str_replace('{{username}}', strtoupper($name), $message);
            $messa = str_replace('{{token}}', $user->lastPwd, $messag);
            $mess = str_replace('{{platform}}', 'Tritek Careers', $messa);
            Mail::send('notification', ['template' => $mess], function ($mail) use ($temp, $user) {
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

        $name = $user->companyName ? $user->companyName : $user->name;

        $temp = MailTemplates::where('name', 'OTP')->first();
        if ($temp) {
            $message = $temp->template;
            $messag = str_replace('{{username}}', strtoupper($name), $message);
            $messa = str_replace('{{token}}', $user->lastPwd, $messag);
            $mess = str_replace('{{platform}}', 'Tritek Careers', $messa);
            Mail::send('notification', ['template' => $mess], function ($mail) use ($temp, $user) {
                $mail->to($user->email, $user->username);
                $mail->subject('Tritek Careers - Reset Password OTP');
            });

            return $this;
        }

        abort(422, 'Template Not Found');
    }

    public function contactAcknowledge(ContactMessages $contact)
    {
        $temp = MailTemplates::where('name', 'CONTACT_ACKNOWLEDGEMENT')->first();
        if ($temp) {
            $message = $temp->template;
            $messag = str_replace('{{username}}', strtoupper($contact->name), $message);
            $mess = str_replace('{{platform}}', 'Tritek Careers', $messag);
            Mail::send('notification', ['template' => $mess], function ($mail) use ($temp, $contact) {
                $mail->to($contact->email, $contact->name);
                $mail->subject('Query Acknowledgement | Tritek Careers');
            });

            return $this;
        }

        abort(422, 'Template Not Found');
    }

    public function contactAdmin(ContactMessages $contact)
    {
        $temp = MailTemplates::where('name', 'CONTACT_ADMIN')->first();
        if ($temp) {
            $message = $temp->template;
            $messag = str_replace('{{username}}', 'Admin', $message);
            $mess1 = str_replace('{{platform}}', 'Tritek Careers', $messag);
            $mess2 = str_replace('{{name}}', $contact->name, $mess1);
            $mess3 = str_replace('{{email}}', $contact->email, $mess2);
            $mess3 = str_replace('{{message}}', $contact->message, $mess3);
            Mail::send('notification', ['template' => $mess3], function ($mail) use ($temp, $contact) {
                $mail->to("info@tritekconsulting.co.uk");
                $mail->subject('New Query | Tritek Careers');
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
