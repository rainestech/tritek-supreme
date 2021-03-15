<?php

namespace Rainestech\Personnel\Controllers;

use Rainestech\AdminApi\Controllers\BaseApiController;
use Rainestech\Personnel\Entity\Candidates;
use Rainestech\Personnel\Entity\Recruiters;
use Rainestech\Personnel\Requests\SearchRequest;
use Rainestech\Personnel\Requests\ShortListRequest;

class SearchController extends BaseApiController
{
    public function search(SearchRequest $request) {
        $search = collect();
        foreach ($request->input('skills') as $term) {
            foreach(Candidates::where('skillSet', 'like', '%'. $term . '%')->get() as $q) {
                $search->push($q);
            }
        }

        $weight = $search->countBy('email')->all();
        $resp = [];

        foreach ($search->unique('id')->values()->all() as $val) {
            $val['weight'] = $weight[$val['email']];
            $resp[] = $val;
        }

        array_multisort(array_column($resp, 'weight'), SORT_DESC, $resp);

        return response()->json($resp);
    }

    public function myShortlist() {
        $user = auth('api')->user();
        if ($user->companyName) {
            $shortList = Recruiters::where('userId', $user->id)->first();
            return response()->json($shortList->candidates);
        } else {
            $shortList = Recruiters::with(['candidates' => function ($query) use ($user) {
                    $query->where('userId', $user->id); }])
                ->get();

            return response()->json($shortList);
        }
    }

    public function shortList(ShortListRequest $request) {
        $profile = Recruiters::where('userId', auth('api')->id())->first();

        if (strtolower($request->input('action')) == 'add')
            $profile->candidates()->attach($request->input('id'));

        else
            $profile->candidates()->detach($request->input('id'));

        return response()->json([]);
    }
}
