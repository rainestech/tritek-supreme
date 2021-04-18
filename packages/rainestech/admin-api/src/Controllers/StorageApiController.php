<?php

namespace Rainestech\AdminApi\Controllers;

use Illuminate\Support\Facades\Storage;
use Rainestech\AdminApi\Entity\FileStorage;
use Rainestech\AdminApi\Entity\Users;
use Rainestech\AdminApi\Requests\StorageRequest;
use Rainestech\AdminApi\Utils\LocalStorage;
use Rainestech\Personnel\Entity\Recruiters;

class StorageApiController extends BaseApiController {
    use LocalStorage;

    public function save(StorageRequest $request) {
        $fs = $this->saveLocal($request);

        if ($request->get('tag') == 'passport') {
            $user = Users::find($fs->objID);

            if ($user) {
                $user->passportID = $fs->id;
                $user->update();
            }
        }
        if ($request->get('tag') == 'logo') {
            $profile = Recruiters::find($fs->objID);

            if ($profile) {
                $user = Users::find($profile->userId);
                $user->passportID = $fs->id;
                $user->update();
            }
        }
        return response()->json($fs);
    }

    public function edit(StorageRequest $request) {
        $fs = $this->editFile($request);
        return response()->json($fs);
    }

    public function getFile($link) {
        if (!$fs = FileStorage::where('link', $link)->first())
            abort(404, 'File Not Found');
//        clock($fs->link);
//        clock(Storage::disk('app')->getVisibility($fs->tag . '/' . $fs->link));
        $file = storage_path('app' . DIRECTORY_SEPARATOR . $fs->tag . DIRECTORY_SEPARATOR . $fs->link);

        $response = response()->file($file);
        if (ob_get_length()) ob_end_clean();
        return $response;
    }

    public function getFs($id) {
        return response()->json(FileStorage::findOrFail($id));
    }

    public function delete($id) {
        $fs = FileStorage::findOrFail($id);

        $this->deleteFile($fs->tag, $fs->link);
        $fs->delete();

        return response()->json($fs);
    }
}
