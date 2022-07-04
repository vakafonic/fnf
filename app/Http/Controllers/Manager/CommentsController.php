<?php
/**
 * Created by Artdevue.
 * User: artdevue - CommentsController.php
 * Date: 2020-02-15
 * Time: 00:01
 * Project: gamesgo.club
 */

namespace App\Http\Controllers\Manager;


use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CommentsController extends Controller
{
    /**
     * constructor.
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
        parent::__construct();
    }

    public function index()
    {
        if (!$this->user->isRoleAction('comments_view')) {
            return $this->get403Code();
        }

        return view('manager.comments.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function all(Request $request)
    {
        if (!$this->user->isRoleAction('comments_view')) {
            return $this->get403Code();
        }

        // Get all criteria
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);
        $order = $request->input('order', 'desc');
        $sort = $request->input('sort', 'created_at');
        $confirmed = $request->input('confirmed', 0);

        $rows = [];
        $query = Comment::query();

        if ($confirmed < 3) {
            $query->where('confirmed', (int)$confirmed);
        }

        $total = $query->count();

        $query->orderBy($sort, $order)
            ->skip($offset)
            ->take($limit);

        $comments = $query->get();
        foreach ($comments as $comment)
        {
            if ($comment->game_id > 0) {
                $rows[] = [
                    'id' => $comment->id,
                    'name' => !is_null($comment->user_id) && !empty($comment->user) ? $comment->user->username : 'Гость',
                    'text' => $comment->text,
                    'ip'  => $comment->ip_user,
                    'game' => $comment->game->langsArray(1)->name,
                    'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $comment->created_at)->format('d-m-Y H:i'),
                    'confirmed' => $comment->confirmed,
                    'url_game' => $comment->game->url
                ];
            }
        }

        return response()->json(compact('rows', 'total'), 200);
    }

    public function edit(Comment $comment)
    {
        if (!$this->user->isRoleAction('comments_edit')) {
            return $this->get403Code();
        }


        return view('manager.comments.edit', compact('comment'));
    }

    public function editPost(Request $request, Comment $comment)
    {
        if (!$this->user->isRoleAction('comments_edit')) {
            return $this->get403Code();
        }

        $input = $request->except('_method', '_token');

        $update_array = ['name' => $input['name'], 'text' => $input['text']];

        $comment->update($update_array);

        $message = 'Вы удачно обновили комментарий "' . $comment->id . '"';

        if ($input['action'] == 1) {
            return Redirect::route('m.comment.edit', ['comment' => $comment->id])->with('success', $message);
        }

        return redirect()->route('m.comments.index')->with('success', $message);
    }


    /**
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function public(Comment $comment)
    {
        if (!$this->user->isRoleAction('comments_edit')) {
            return $this->get403Code();
        }

        $success = false;
        $message = 'Ошибка публикации комментария ';

        try {
            $comment->update(['confirmed' => 1]);
            $success = true;
            $message = 'Вы опубликовали комментарий ';

        } catch (\Exception $e) {
            // do task when error
            $message =  $e->getMessage();
        }

        return response()->json(compact('success', 'message'), 200);
    }

    /**
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Comment $comment)
    {
        if (!$this->user->isRoleAction('comments_delete')) {
            return $this->get403Code();
        }

        $success = false;
        $message = 'Ошибка публикации комментария ';

        try {
            $comment->delete();
            $success = true;
            $message = 'Вы удалили комментарий ';

        } catch (\Exception $e) {
            // do task when error
            $message =  $e->getMessage();
        }

        return response()->json(compact('success', 'message'), 200);
    }
}