<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use Auth;
use DB;
class ChatsController extends Controller
{
    //Add the below functions
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('chat');
    }

    public function fetchMessages()
    {
        $query = DB::table('messages')
                    ->join('users','messages.user_id','=','users.id')
                    ->select('users.name','messages.id as msg_id','messages.message')
                    ->orderBy('messages.created_at','DESC')->get();

        return $query;
    }

    public function editMessage(Request $request)
    {
        if($request->input('message')===null || $request->input('msg_id')===null)
        {
            return response()->json(['Error' => 'Bad Request'], 400);
        }
        else{
            $user = Auth::user();

            if($user)
            {
                $user_id = $user->id;
                $msg_id = $request->input('msg_id');
                $message = $request->input('message');

                $query=DB::table('messages')
                        ->where('id','=',$msg_id)
                        ->where('user_id','=',$user_id)
                        ->first();

                if($query)
                {
                    $data['message'] = $message;

                    $update = DB::table('messages')
                        ->where('id','=',$msg_id)
                        ->where('user_id','=',$user_id)
                        ->update(['message' => $message]);

                    if($update == 1)
                    {
                        return response()->json(['message' => 'Message updated successfully'], 200);
                    }

                }
                else
                {
                    return response()->json(['Error' => 'Bad Request'], 400);
                }

            }
            return response()->json(['Error' => 'Bad Request'], 400);
        }

    }

    public function destroy(Request $request)
    {
        if($request->input('msg_id')===null)
        {
            return response()->json(['Error' => 'Msg Id is null'], 400);
        }
        else
        {
            $user = Auth::user();

            if($user)
            {
                $user_id = $user->id;
                $msg_id = $request->input('msg_id');
                $query=DB::table('messages')
                        ->where('id','=',$msg_id)
                        ->where('user_id','=',$user_id)
                        ->first();

                if($query)
                {
                    $delete = Message::findOrFail($msg_id);

                    $delete->delete();
                    return response()->json(['message' => 'Message deleted successfully'], 200);
                }
                else{
                    return response()->json(['Error' => 'Failed to delete data'], 400);
                }
            }

        }

        return response()->json(['Error' => 'Bad Request'], 400);

    }

    public function sendMessage(Request $request)
    {

        if($request->input('message')===null)
        {
            return response()->json(['Error' => 'Bad Request'], 400);
        }
        else
        {
            $user = Auth::user();
            if($user)
            {
                $message = Message::create([
                    'user_id' => $user->id,
                    'message' => $request->input('message')
                ]);

                return response()->json(['status' => 'Message sent successfully'], 200);
            }
        }

        return response()->json(['Error' => 'Bad Request'], 400);
    }
}

