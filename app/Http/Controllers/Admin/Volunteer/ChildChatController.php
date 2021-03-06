<?php

namespace App\Http\Controllers\Admin\Volunteer;

use App\Filters\ChatFilter;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;
use App\Models\Child;

class ChildChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(ChatFilter $filters, Child $child)
    {
        $this->authorize('listChild', [Chat::class, $child]);

        $child->load(['faculty', 'users:users.id,first_name,last_name', 'volunteer']);
        $child->append('gift_state_label');

        $chats = $child->chats()->filter($filters)->with([
            'volunteer' => function ($query) {
                return $query->withCount(['children', 'chats']);
            }
        ])->latest()->get();

        return api_success(['chats' => $chats, 'child' => $child]);
    }

    public function update(Request $request, Child $child)
    {
        $this->authorize('updateChild', [Chat::class, $child]);

        if ($request->action == 'close-all') {
            $child->activeChats->each->close();
            $child->chats->each->answerMessages();

            return api_success();
        }

        return [];
    }
}