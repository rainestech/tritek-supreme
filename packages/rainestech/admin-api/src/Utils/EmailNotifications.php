<?php

namespace Rainestech\AdminApi\Utils;

use Mail;
use Rainestech\AdminApi\Entity\MailTemplates;
use Rainestech\AdminApi\Entity\Users;

class EmailNotifications
{
    public function testMail()
    {
        $temp = MailTemplates::find(1);
        if ($temp) {
            $message = $temp->template;
            Mail::send('notification', ['template' => $message], function ($mail) {
                $mail->to('ayoola@rainestech.com');
                $mail->subject('Tritek Test Mail');
            });
        }

        return $this;
    }

    public function sendWelcome(Users $user)
    {
        $temp = MailTemplates::where('name', 'welcome-supreme')->first();
        if ($temp) {
            $message = $temp->template;
            $msg = str_replace('{{username}}', strtoupper($user->companyName), $message);
            Mail::send('notification', ['template' => $msg], function ($mail) use ($temp, $user) {
                $mail->to($user->email, $user->companyName);
                $mail->subject('Welcome to Tritek Careers');
            });
        }

        return $this;
    }

    public function sendWaitingApproval(Users $user)
    {
        $temp = MailTemplates::where('name', 'verification_waiting')->first();
        if ($temp) {
            $message = $temp->template;
            $msg = str_replace('{{username}}', strtoupper($user->companyName), $message);
            Mail::send('notification', ['template' => $msg], function ($mail) use ($temp, $user) {
                $mail->to($user->email, $user->companyName);
                $mail->subject('Verification In Progress | Tritek Careers');
            });
        }

        return $this;
    }

    public function sendVerificationApproved(Users $user)
    {
        $temp = MailTemplates::where('name', 'verification_approved')->first();
        if ($temp) {
            $message = $temp->template;
            $msg = str_replace('{{username}}', strtoupper($user->companyName), $message);
            Mail::send('notification', ['template' => $msg], function ($mail) use ($temp, $user) {
                $mail->to($user->email, $user->companyName);
                $mail->subject('Verification Approved | Tritek Careers');
            });
        }

        return $this;
    }

    public function candidateRequestAlert(Users $user)
    {
        $temp = MailTemplates::where('name', 'candidate_request_alert')->first();
        if ($temp) {
            $message = $temp->template;
            $msg = str_replace('{{username}}', strtoupper($user->companyName), $message);
            Mail::send('notification', ['template' => $msg], function ($mail) use ($temp, $user) {
                $mail->to($user->email, $user->companyName);
                $mail->subject('Request Received | Tritek Careers');
            });
        }

        return $this;
    }

    public function candidateRequestAdmin($user)
    {
        $temp = MailTemplates::where('name', 'candidate_request_alert')->first();
        if ($temp) {
            $message = $temp->template;
            $msg = str_replace('{{username}}', $user->companyName, $message);

            Mail::send('notification', ['template' => $msg], function ($mail) use ($temp, $user) {
                $mail->to("info@tritekconsulting.co.uk");
                $mail->subject('Request Received | Tritek Careers');
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
            Mail::send('notification', ['template' => $msg], function ($mail) use ($temp, $user) {
                $mail->to($user->user_email, $user->user_nicename);
                $mail->subject('Tritek Careers Password Change');
            });
        }

        return $this;
    }


}
