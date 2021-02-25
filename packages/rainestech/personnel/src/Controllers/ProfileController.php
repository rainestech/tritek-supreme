<?php

namespace Rainestech\Personnel\Controllers;

use Rainestech\AdminApi\Controllers\BaseApiController;
use Rainestech\AdminApi\Entity\Users;
use Rainestech\AdminApi\Utils\ErrorResponse;
use Rainestech\Personnel\Entity\Candidates;
use Rainestech\Personnel\Entity\Recruiters;
use Rainestech\Personnel\Requests\CandidatesRequest;
use Rainestech\Personnel\Requests\RecruiterRequest;

class ProfileController extends BaseApiController {
    use ErrorResponse;
    public function recruiters() {
        return response()->json(Recruiters::all());
    }

    public function candidates() {
        return response()->json(Candidates::all());
    }

    public function getRecruiterByUserID($userID) {
        if (!$p = Recruiters::where('userId', $userID)->first())
            return $this->jsonError(404, 'Recruiter Record Not Found!');

        return response()->json($p);
    }

    public function getMyProfile() {
        $user = auth('api')->user();
        if ($user->companyName) {
            if (!$p = Recruiters::with('docs', 'logo')->where('userId', auth('api')->id())->first())
                return $this->jsonError(404, 'Recruiter Record Not Found!');

            return response()->json($p);
        }

        if (!$p = Candidates::with('docs', 'projects')->where('userId', auth('api')->id())->first())
            return $this->jsonError(404, 'Candidate Record Not Found!');

        return response()->json($p);
    }

    public function saveRecruiter(RecruiterRequest $request)
    {
        if ($request->has('id')) {
            return $this->updateRecruiter($request);
        }

        $pID = $request->input('user.id');
        if (Recruiters::where('userId', $pID)->exists())
            return $this->jsonError(400, 'Record Exists for selected member. Edit Record instead.');

        $recruiters = new Recruiters($request->except(['logo', 'user']));
        $recruiters->userId = $request->input('user.id');
        $recruiters->fsId = $request->input('logo.id');
        $recruiters->save();

        $user = Users::find($recruiters->userId);
        if ($user) {
            $user->passportID = $recruiters->fsId;
            $user->update();
        }

        $recruiters->loadWith();

        return response()->json($recruiters);
    }

    public function updateRecruiter(RecruiterRequest $request) {
        if (!$recruiters = Recruiters::find($request->input('id')))
            return $this->jsonError(404, 'Record Not Found for Update');

        $recruiters->fill($request->except(['user', 'logo', 'id']));
        $recruiters->userId = $request->input('user.id');
        $recruiters->fsId = $request->input('logo.id');
        $recruiters->update();

        return response()->json(Recruiters::where('id', $recruiters->id)
            ->first());
    }

    public function saveCandidate(CandidatesRequest $request)
    {
        if ($request->has('id')) {
            return $this->updateCandidate($request);
        }

        $pID = $request->input('user.id');
        if (Candidates::where('userId', $pID)->exists())
            return $this->jsonError(400, 'Record Exists for selected candidate. Edit Record instead.');

        $candida = new Candidates($request->except(['user']));
        $candida->userId = $request->input('user.id');
        $candida->save();

        return response()->json($candida);
    }

    public function updateCandidate(CandidatesRequest $request) {
        if (!$candidates = Candidates::find($request->input('id')))
            return $this->jsonError(404, 'Record Not Found for Update');

        $candidates->fill($request->except(['user', 'logo', 'id']));
        $candidates->userId = $request->input('user.id');
        $candidates->update();

        return response()->json(Candidates::with('user')
            ->where('id', $candidates->id)
            ->first());
    }

    public function removeRecruiter($id) {
        $recruiter = Recruiters::find($id);

        if (!$recruiter) {
            return $this->jsonError(404, 'Profile Not Found!');
        }

        $recruiter->user()->delete();
        $recruiter->delete();
        return response()->json([]);
    }

    public function removeCandidate($id) {

    }
}
