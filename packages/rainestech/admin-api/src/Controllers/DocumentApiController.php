<?php

namespace Rainestech\AdminApi\Controllers;

use Illuminate\Support\Facades\Storage;
use Rainestech\AdminApi\Entity\Documents;
use Rainestech\AdminApi\Entity\FileStorage;
use Rainestech\AdminApi\Entity\Users;
use Rainestech\AdminApi\Requests\DocumentRequest;
use Rainestech\AdminApi\Requests\StorageRequest;
use Rainestech\AdminApi\Utils\LocalStorage;
use Rainestech\Personnel\Entity\Recruiters;

class DocumentApiController extends BaseApiController {
    use LocalStorage;

    public function save(StorageRequest $request) {
        $fs = $this->saveLocalWithName($request);
        return response()->json($fs);
    }

    public function saveDoc(DocumentRequest $request) {
        $doc = new Documents($request->except(['file', 'id']));
        $doc->fileId = $request->input('file.id');
        $doc->editor = auth('api')->id();

        $doc->save();
        $doc->loadWith();
        return response()->json($doc);
    }

    public function editDoc(DocumentRequest $request) {
        if (!$doc = Documents::find($request->input('id'))) {
            return $this->jsonError(404, "Document Not Found");
        }

        $doc->fill($request->except(['file', 'id']));
        $doc->fileId = $request->input('file.id');
        $doc->editor = auth('api')->id();

        $doc->update();
        $doc->loadWith();
        return response()->json($doc);
    }

    public function edit(StorageRequest $request) {
        $fs = $this->editFileWithName($request);
        return response()->json($fs);
    }

    public function getFile($link) {
        clock('am here');
        if (!$fs = FileStorage::where('link', $link)->first())
            abort(404, 'File Not Found');
        $file = storage_path('app' . DIRECTORY_SEPARATOR . $fs->tag . DIRECTORY_SEPARATOR . $fs->link);

        $response = response()->file($file);
        if (ob_get_length()) ob_end_clean();
        return $response;
    }

    public function delete($id) {
        $fs = FileStorage::findOrFail($id);

        $this->deleteFile($fs->tag, $fs->link);
        $fs->delete();

        return response()->json($fs);
    }

    public function deleteDocument($id) {
        $fs = Documents::findOrFail($id);

        $this->deleteFile($fs->file->tag, $fs->file->link);
        $fs->delete();

        return response()->json([]);
    }

    public function getMyDocuments() {
        return response()->json(Documents::where('editor', auth('api')->id())->get());
    }

    public function getUserDocuments($id) {
        return response()->json(Documents::where('editor', $id)
            ->where('private', 0)
            ->get());
    }
}
