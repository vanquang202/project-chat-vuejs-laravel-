<?php

namespace App\Http\Controllers\Api\Comment;

use App\Events\ResetComment;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\NotifiToUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    protected $comment;
    protected $user;
    protected $post;
    public function __construct(Comment $comment, User  $user, Post  $post)
    {
        $this->comment = $comment;
        $this->user = $user;
        $this->post = $post;
    }
    public function store(Request $request)
    {
        $data = $this->comment->a_create($request->data);
        $userFindByComment = $this->post->a_show($request->data['post_id'], ['user']);


        if (auth()->user()->id != $userFindByComment->user->id) {
            $messgae = auth()->user()->name . ' đã bình luận bài viết của bạn ';
            $userFindByComment->user->notify(new NotifiToUser($messgae, $request->data['post_id']));
        }

        if (isset($request->data['comment_id'])) {
            $data = $this->comment->a_show($request->data['comment_id'], ['user']);
            $messgae = auth()->user()->name . ' đã  trả lời bình luận  của bạn ';
            $data->user->notify(new NotifiToUser($messgae, $request->data['post_id']));
        }

        if ($data) {
            return response()->json([
                'code' => Response::HTTP_OK,
            ]);
        } else {
            return response()->json([
                'code' => Response::HTTP_BAD_GATEWAY,
            ]);
        }
    }

    public function increment($id)
    {
        $list = explode(',', auth()->user()->vote_comment ?? '');
        if (!in_array($id, $list)) {
            $post = $this->comment::find($id);
            $post->increment('vote');

            $list = implode(',', $list) . ',' . (string)  $id;
            auth()->user()->update([
                'vote_comment' => $list
            ]);
            return 1;
        }
        return 0;
    }

    public function decrement($id)
    {
        $list = explode(',', auth()->user()->vote_comment ?? '');
        if (!in_array($id, $list)) {
            $post = $this->comment::find($id);
            $post->decrement('vote');

            $list = implode(',', $list) . ',' . (string)  $id;
            auth()->user()->update([
                'vote_comment' => $list
            ]);
            return 1;
        }
        return 0;
    }

    public function destroy($id)
    {
        try {
            $comment = $this->comment::find($id);
            $comment->comment()->delete();
            $comment->delete();
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }
    public function show($id)
    {
        try {
            $comment = $this->comment::find($id);
            return response()->json([
                'status' => true,
                'payload' => $comment,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'payload' => 'Không thể chỉnh sửa',
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'content' => 'required',
            ]
        );
        if ($validator->fails()) return response()->json(
            [
                'status' => 'error',
                'payload' => 'Nội dung bình luận không được bỏ trống !',
            ]
        );
        try {
            $this->comment::find($id)->update([
                'content' => $request->content,
            ]);
            return response()->json(
                [
                    'status' => true,
                    'payload' => 'Thành công !',
                ]
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => true,
                    'payload' => 'Thất bại !',
                ]
            );
        }
    }
}