<?php

namespace Rainestech\Personnel\Controllers;

use Rainestech\AdminApi\Controllers\BaseApiController;
use Rainestech\AdminApi\Utils\EmailNotifications;
use Rainestech\AdminApi\Utils\ErrorResponse;
use Rainestech\AdminApi\Utils\LocalStorage;
use Rainestech\Personnel\Entity\RecruiterCandidates;
use Rainestech\Personnel\Requests\RecruiterCandidateBulkRequest;
use Rainestech\Personnel\Requests\RecruiterCandidateRequest;

class RequestController extends BaseApiController {
    use ErrorResponse, LocalStorage;

    public function index() {
        return response()->json(RecruiterCandidates::with(['candidate', 'recruiter'])->orderBy('id', 'desc')->get());
    }

    public function indexList() {
        return response()->json(RecruiterCandidates::all());
    }

    public function getRecruiterRequest($id) {
        return RecruiterCandidates::with(['candidate', 'recruiter'])
            ->where('rId', $id)
            ->orderBy('id', 'desc')->get();
    }

    public function getRecruiterRequestList($id) {
        return response()->json(RecruiterCandidates::with(['candidate', 'recruiter'])
            ->where('rId', $id)
            ->orderBy('id', 'desc')->get());
    }

    public function getCandidateRequestList($id) {
        return response()->json(RecruiterCandidates::with(['candidate', 'recruiter'])
            ->where('cId', $id)
            ->orderBy('id', 'desc')->get());
    }

    public function save(RecruiterCandidateRequest $request) {
        if (!$req = RecruiterCandidates::where('cid', $request->input('cid'))
                        ->where('rid', $request->input('rid'))
                        ->first()) {
            $req = new RecruiterCandidates();
        }

        $req->fill($request->except(['id']));
        $req->save();

        if ($req->requested == 1) {
            $email = new EmailNotifications();

            $email->candidateRequestAlert($req->recruiter->user);
//            $email->candidateRequestAdmin($req->recruiter->user);
        }

        $req->load(['candidate', 'recruiter']);
        return response()->json($req);
    }

    public function toggleRequest($id) {
        if (!$request = RecruiterCandidates::find($id)) {
            return $this->jsonError(406, 'Record Not Found!');
        }

        $request->approved = $request->approved == 1 ? 0 : 1;
        $request->save();
        $request->load(['candidate', 'recruiter']);

//        if ($request->approved == 1) {
//            $email = new EmailNotifications();
//
//            $email->($req->recruiter->user);
//            $email->candidateRequestAdmin($req->recruiter->user);
//        }

        return response()->json($request);
    }

    public function saveAll(RecruiterCandidateBulkRequest $request) {
        foreach ($request->all() as $item) {
            if (!$req = RecruiterCandidates::where('cid', $item['cid'])
                            ->where('rid', $item['rid'])
                            ->first()) {
                $req = new RecruiterCandidates();
            }

            $req->cid = $item['cid'];
            $req->rid = $item['rid'];
            $req->requested = $item['requested'];
            $req->save();
        }

        $mail = new EmailNotifications();
        $mail->candidateRequestAlert($req->recruiter->user);
        $mail->candidateRequestAdmin($req->recruiter->user);

        return response()->json([]);
    }

    public function delete($id) {
        if (!$request = RecruiterCandidates::find($id)) {
            $this->jsonError(404, 'Request Not Found!');
        }

        $request->delete();

        return response()->json([]);
    }
}
