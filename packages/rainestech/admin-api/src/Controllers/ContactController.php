<?php


namespace Rainestech\AdminApi\Controllers;


use Rainestech\AdminApi\Entity\ContactMessages;
use Rainestech\AdminApi\Requests\ContactRequest;

class ContactController extends BaseApiController {
    public function index() {
        return response()->json(ContactMessages::all());
    }

    public function save(ContactRequest $request) {
        $msg = new ContactMessages();
        $msg->fill($request->except(['id']));
        $msg->save();

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
