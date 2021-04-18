<?php


namespace Rainestech\AdminApi\Controllers;


use Rainestech\AdminApi\Entity\MailTemplates;
use Rainestech\AdminApi\Entity\SmsTemplates;
use Rainestech\AdminApi\Notifications\EmailVerification;
use Rainestech\AdminApi\Requests\MailTemplateRequest;
use Rainestech\AdminApi\Requests\SmsTemplateRequest;
use Rainestech\AdminApi\Utils\EmailNotifications;

class NotificationTemplateController extends BaseApiController {
    public function testMail() {
        $email = new EmailNotifications();
        $email->testMail();
        return true;
    }

    public function mailIndex() {
        return response()->json(MailTemplates::all());
    }

    public function smsIndex() {
        return response()->json(SmsTemplates::all());
    }

    public function saveMailTemplate(MailTemplateRequest $request) {
        $temp = new MailTemplates($request->validated());
        $temp->save();

        return response()->json($temp);
    }

    public function saveSmsTemplate(SmsTemplateRequest $request) {
        $temp = new SmsTemplates($request->validated());
        $temp->save();

        return response()->json($temp);
    }

    public function editMailTemplate(MailTemplateRequest $request) {
        if(!$temp = MailTemplates::find($request->get('id')))
            abort(404, 'Mail Template not found for edit');

        $temp->fill($request->except(['id']));
        $temp->save();

        return response()->json($temp);
    }

    public function editSmsTemplate(SmsTemplateRequest $request) {
        if(!$temp = SmsTemplates::find($request->get('id')))
            abort(404, 'Sms Template not found for edit');

        $temp->fill($request->except(['id']));
        $temp->save();

        return response()->json($temp);
    }

    public function deleteMailTemp($id) {
        if(!$temp = MailTemplates::find($id))
            abort(404, 'Mail Template not found for delete');

        $temp->delete();
        return response()->json($temp);
    }

    public function deleteSmsTemp($id) {
        if(!$temp = SmsTemplates::find($id))
            abort(404, 'Sms Template not found for delete');

        $temp->delete();
        return response()->json($temp);
    }

}
