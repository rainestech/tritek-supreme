<?php

namespace Rainestech\Personnel\Controllers;

use Rainestech\AdminApi\Controllers\BaseApiController;
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

    public function save(RecruiterCandidateRequest $request) {
        if (!$req = RecruiterCandidates::where('cid', $request->input('cid'))
                        ->where('rid', $request->input('rid'))
                        ->first()) {
            $req = new RecruiterCandidates();
        }

        $req->fill($request->except(['id']));
        $req->save();

        $req->load(['candidate', 'recruiter']);
        return response()->json($req);
    }

    public function saveAll(RecruiterCandidateBulkRequest $request) {
        clock($request);

        foreach ($request->all() as $item) {
            if (!$req = RecruiterCandidates::where('cid', $item['cid'])
                            ->where('rid', $item['rid'])
                            ->first()) {
                $req = new RecruiterCandidates();
            }

            $req->fill($request->except(['id']));
            $req->save();
        }

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
