<?php
/**
 * Created by Artdevue.
 * User: artdevue - FeedbacksController.php
 * Date: 2020-02-22
 * Time: 19:51
 * Project: gamesgo.club
 */

namespace App\Http\Controllers\Manager;


use App\Models\Feedback;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FeedbacksController extends Controller
{
    /**
     * constructor.
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
        parent::__construct();

        Carbon::setLocale('ru');
        setlocale(LC_TIME, 'ru');
    }

    public function index()
    {
        return view('manager.feedbacks.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {

        $rows = [];
        // Get all criteria
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);
        $order = $request->input('order', 'desc');
        $sort = $request->input('sort', 'created_at');

        $feedbacks = Feedback::orderBy($sort, $order)->skip($offset)->take($limit)->get();
        foreach ($feedbacks as $feedback) {
            $rows[] = [
                'id' => $feedback->id,
                'email' => $feedback->email,
                'text' => Str::limit($feedback->message, 100),
                'view' => $feedback->view,
                'date' => $feedback->created_at->formatLocalized('%d %B %Y в %H:%M') //Carbon::createFromFormat('Y-m-d H:i:s', $feedback->created_at)->format('d F Y H:i')
            ];
        }

        return response()->json($rows, 200);
    }

    /**
     * @param Feedback $feedback
     * @return \Illuminate\Http\JsonResponse
     */
    public function postFeedback(Feedback $feedback)
    {
        $feedback->update(['view' => 1]);
        $message = nl2br($feedback->message);
        $id = $feedback->id;
        $url = $feedback->url;
        $email = $feedback->email;
        $date = $feedback->created_at->formatLocalized('%d %B %Y в %H:%M');

        $count_no_view = Feedback::whereView(0)->count();

        return response()->json(compact('id', 'message', 'email', 'url', 'date', 'count_no_view'), 200);
    }

    /**
     * @param Feedback $feedback
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function postDeleteMessage(Feedback $feedback)
    {
        $feedback->delete();
        $success = true;
        return response()->json(compact('success'), 200);
    }
}