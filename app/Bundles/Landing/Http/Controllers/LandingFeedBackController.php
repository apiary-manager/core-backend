<?php

namespace App\Http\Controllers\Landing;

use App\Events\FeedBackCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Landing\StoreFeedBack;
use App\Http\Responses\ActionResponse;
use App\Models\Landing\LandingFeedBack;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LandingFeedBackController extends Controller
{
    /**
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function index(int $page = 1): LengthAwarePaginator
    {
        return LandingFeedBack::paginate(10, ['*'], 'LandingFeedBacksList', $page);
    }

    public function store(StoreFeedBack $request): ActionResponse
    {
        $feedback = new LandingFeedBack();

        $feedback->people_title = $request->people_title;
        $feedback->people_email = $request->people_email;
        $feedback->people_phone = $request->people_phone;
        $feedback->message = $request->message;
        $feedback->ip = $request->getClientIp();

        $feedback->save();

        event(new FeedBackCreated($feedback));

        return new ActionResponse(true);
    }
}