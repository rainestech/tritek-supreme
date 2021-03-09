<?php


namespace Rainestech\Personnel\Controllers;


use Rainestech\AdminApi\Controllers\BaseApiController;
use Rainestech\Personnel\Requests\CommentRequest;
use Rainestech\Tasks\Entity\Comments;

class CommentsController extends BaseApiController
{
    public function saveComments(CommentRequest $request) {
        $comment = new Comments();
        $comment->fill($request->except(['schedule', 'task']));

        if ($request->input('task.id')) {
            $comment->taskId = $request->input('task.id');
        } else if ($request->input('schedule.id')) {
            $comment->taskId = $request->input('schedule.id');
        } else {
            return $this->jsonError(422, 'Comment Precondition failed');
        }

        $comment->save();
        $comment->refresh();

        return response()->json($comment);
    }

    public function deleteComment($id) {
        if (!$comment = Comments::find($id)) {
            return $this->jsonError(404, 'Comment Not Found');
        }

        $comment->delete();
        return response()->json([]);
    }
}
