<?php


namespace Rainestech\AdminApi\Controllers;


use Rainestech\AdminApi\Entity\ContactMessages;
use Rainestech\AdminApi\Notifications\EmailVerification;
use Rainestech\AdminApi\Requests\ContactRequest;
use Rainestech\AdminApi\Utils\EmailNotifications;

class ContactController extends BaseApiController {
    public function index() {
        return response()->json(ContactMessages::all());
    }

    public function save(ContactRequest $request) {
        $msg = new ContactMessages();
        $msg->fill($request->except(['id']));
        $msg->save();

        $mail = new EmailVerification();
        $mail->contactAcknowledge($msg)->contactAdmin($msg);

        return response()->json($msg);
    }

    public function update(ContactRequest $request) {
        $msg = ContactRequest::find($request->input('id'));
        if (!$msg) {
            return $this->jsonError(404, 'Resource not found');
        }

        $msg->fill($request->except(['id']));
        $msg->save();

        return response()->json($msg);
    }

    public function remove($id) {
        if (!$msg = ContactMessages::find($id)) {
            return $this->jsonError(404, 'Resource not found');
        }

        $msg->delete();
        return response()->json([]);
    }


}
