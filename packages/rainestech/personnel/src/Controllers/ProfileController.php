<?php

namespace Rainestech\Personnel\Controllers;

use Rainestech\AdminApi\Controllers\BaseApiController;
use Rainestech\Personnel\Entity\Candidates;
use Rainestech\Personnel\Entity\Recruiters;
use Rainestech\Personnel\Requests\CandidatesRequest;
use Rainestech\Personnel\Requests\RecruiterRequest;

class ProfileController extends BaseApiController {
    public function recruiters() {
        return response()->json(Recruiters::all());
    }

    public function candidates() {
        return response()->json(Candidates::all());
    }

    public function getRecruiterByUserID($userID) {
        if (!$p = Recruiters::where('userId', $userID)->first())
            abort(404, 'Recruiter Record Not Found!');

        return response()->json($p);
    }

    public function getMyProfile() {
        $user = auth('api')->user();
        if ($user->companyName) {
            if (!$p = Recruiters::with('docs', 'logo')->where('userId', auth('api')->id())->first())
                abort(404, 'Recruiter Record Not Found!');

            return response()->json($p);
        }

        if (!$p = Candidates::with('docs', 'projects')->where('userId', auth('api')->id())->first())
            abort(404, 'Candidate Record Not Found!');

        return response()->json($p);
    }

    public function saveRecruiter(RecruiterRequest $request)
    {
        if ($request->has('id')) {
            return $this->updateRecruiter($request);
        }

        $pID = $request->input('user.id');
        if (Recruiters::where('userId', $pID)->exists())
            abort(400, 'Record Exists for selected member. Edit Record instead.');

        $recruiters = new Recruiters($request->except(['logo', 'user']));
        $recruiters->userId = $request->input('user.id');
        $recruiters->fsId = $request->input('logo.id');
        $recruiters->save();

        return response()->json($recruiters);
    }

    public function updateRecruiter(RecruiterRequest $request) {
        if (!$recruiters = Recruiters::find($request->input('id')))
            abort(404, 'Record Not Found for Update');

        $recruiters->fill($request->except(['user', 'logo', 'id']));
        $recruiters->userId = $request->input('user.id');
        $recruiters->fsId = $request->input('logo.id');
        $recruiters->update();

        return response()->json(Recruiters::with('user', 'occupation', 'bank')
            ->where('id', $recruiters->id)
            ->first());
    }

    public function saveCandidate(CandidatesRequest $request)
    {
        if ($request->has('id')) {
            return $this->updateCandidate($request);
        }

        $pID = $request->input('user.id');
        if (Candidates::where('userId', $pID)->exists())
            abort(400, 'Record Exists for selected candidate. Edit Record instead.');

        $candida = new Candidates($request->except(['user']));
        $candida->userId = $request->input('user.id');
        $candida->save();

        return response()->json($candida);
    }

    public function updateCandidate(CandidatesRequest $request) {
        if (!$candidates = Candidates::find($request->input('id')))
            abort(404, 'Record Not Found for Update');

        $candidates->fill($request->except(['user', 'logo', 'id']));
        $candidates->userId = $request->input('user.id');
        $candidates->update();

        return response()->json(Candidates::with('user')
            ->where('id', $candidates->id)
            ->first());
    }

    public function removeRecruiter($id) {

    }

    public function removeCandidate($id) {

    }
}
