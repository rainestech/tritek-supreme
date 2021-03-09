<?php

namespace Rainestech\Tasks\Controllers;

use Carbon\Carbon;
use Rainestech\AdminApi\Controllers\BaseApiController;
use Rainestech\AdminApi\Entity\FileStorage;
use Rainestech\AdminApi\Utils\LocalStorage;
use Rainestech\Tasks\Entity\Tasks;
use Rainestech\Tasks\Requests\TaskFilesRequest;
use Rainestech\Tasks\Requests\TaskPositionRequest;
use Rainestech\Tasks\Requests\TasksRequest;

class TaskController extends BaseApiController {
    use LocalStorage;

    public function getTaskByChannel($channelId) {
        return response()->json(Tasks::where('channelId', $channelId)->orderBy('position', 'asc')->get());
    }

    public function saveTask(TasksRequest $request) {
        $task = new Tasks();
        $task->fill($request->except(['assignedTo', 'channel', 'parent', 'docs']));

        if ($request->input('parent.id')) {
            $task->parentId = $request->input('parent.id');
        }

        $task->channelId = $request->input('channel.id');
        $task->save();

        if ($request->has('docs')) {
            foreach ($request->input('docs') as $d) {
                $task->docs()->attach($d['id']);
            }
        }

        if ($request->has('assignedTo')) {
            foreach ($request->input('assignedTo') as $a) {
                $task->assignedTo()->attach($a['id']);
            }
        }

        $task->refresh();
        return response()->json($task);
    }

    public function editTask(TasksRequest $request) {
        if(!$task = Tasks::find($request->input('id'))) {
            return $this->jsonError(404, 'Task Not Found for Edit');
        }

        $task->fill($request->except(['assignedTo', 'channel', 'parent', 'docs']));

        if ($request->input('parent.id')) {
            $task->parentId = $request->input('parent.id');
        }

        $task->channelId = $request->input('channel.id');

        $task->save();

        if ($request->has('assignedTo')) {
            foreach ($task->assignedTo as $u) {
                $task->assignedTo()->detach($u->id);
            }

            foreach ($request->input('assignedTo') as $a) {
                $task->assignedTo()->attach($a['id']);
            }
        }

        $task->refresh();
        return response()->json($task);
    }

    public function attachFile(TaskFilesRequest $request) {
        $task = Tasks::find($request->input('id'));
        $task->docs()->attach($request->input('doc.id'));

        $task->refresh();
        return response()->json($task);
    }

    public function updatePosition(TaskPositionRequest $request) {
        foreach ($request->all() as $item) {
            $task = Tasks::find($item['id']);
            $task->position = $item['position'];
            $task->tab = $item['tab'];

            if (strtolower($item['tab']) == 'done') {
                $task->doneDate = Carbon::now();
                $task->doneById = auth('api')->id();
            }

            $task->save();
        }

        return response()->json([]);
    }

    public function detachFile(TaskFilesRequest $request) {
        $task = Tasks::find($request->input('id'));
        $task->docs()->detach($request->input('doc.id'));

        $fileStorage = FileStorage::find($request->input('docs.id'));
        $this->removeFile($fileStorage);

        $task->refresh();
        return response()->json($task);
    }

    public function deleteTask($id) {
        if (!$task = Tasks::find($id)) {
            return $this->jsonError(404, 'Task Not Found for Delete');
        }

        $docs = $task->docs;
        foreach ($docs as $doc) {
            $task->docs()->detach($doc->id);
            $this->removeFile($doc);
        }

        foreach ($task->children as $child) {
            foreach ($child->docs as $doc) {
                $task->docs()->detach($doc->id);
                $this->removeFile($doc);
            }
            $child->delete();
        }

        $task->delete();
        return response()->json([]);
    }
}
