<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

class UserController extends Controller
{

    public function profile()
    {
        return view('profile');
    }

    public function list(){
        $postRs=DB::table('tbl_post')
        ->rightjoin('users','users.id','=','tbl_post.user_id')
        ->select('tbl_post.id','tbl_post.body','tbl_post.created_at','users.name')
        ->orderBy('tbl_post.created_at','DESC')
        ->get();
        // dd($post);

                $post_list = $postRs->map(function($post) {
                    $post->liked_user_count = DB::table('tbl_like_post')->where('post_id', $post->id)->count();
                    $post->cmt_user_count = DB::table('tbl_comments')->where('post_id', $post->id)->count();
                    
                    return $post;
                });

        return view('list')->with('post',$post_list);
    }
    
    public function add_interest(Request $request){

        $data = [
            'body' => isset($request['body']) ? $request['body'] : '',
            'user_id' => session('loginId'),
            'created_at'=>date('Y-m-d H:i:s')
        ];

        DB::table('tbl_post')->insert($data);
        return redirect('/list');
    }

    public function comments($id){
        
        $post_id=$id;
        $comments=DB::table('tbl_comments')
        ->leftjoin('users','users.id','=','tbl_comments.user_id')
        ->leftjoin('tbl_post','tbl_post.id','=','tbl_comments.post_id')
        ->where('tbl_post.id',$post_id)
        ->where('tbl_comments.parrent_id','=', NULL)
        ->select('users.name','tbl_post.body','tbl_comments.comment','tbl_comments.id','tbl_comments.created_at')
        ->orderBy('tbl_comments.created_at','DESC')
        ->get();

        $head=DB::table('tbl_post')
        ->where('id',$post_id)
        ->first();
        // dd($head);

        $reply=DB::table('tbl_comments')
        ->leftjoin('users','users.id','=','tbl_comments.user_id')
        ->leftjoin('tbl_post','tbl_post.id','=','tbl_comments.post_id')
        ->where('tbl_comments.parrent_id','!=', NULL)
        ->select('users.name','tbl_post.body','tbl_comments.comment','tbl_comments.id','tbl_comments.parrent_id','tbl_comments.created_at')
        ->get();
        // dd($reply);

        $cmt_user_count = DB::table('tbl_comments')->where('post_id', $post_id)->count();
                
        return view('comments')->with('post_id',$id)->with('comments',$comments)->with('reply',$reply)
        ->with('head',$head)
        ->with('cmt_user_count',$cmt_user_count);
    }

    public function comment(Request $request)
    {
        $data = [
            'comment' => isset($request['comment']) ? $request['comment'] : '',
            'user_id' => session('loginId'),
            'post_id' => isset($request['post_id']) ? $request['post_id'] : '',
            'created_at'=>date('Y-m-d H:i:s')
        ];
        // dd($data);
        DB::table('tbl_comments')->insert($data);

        return back();
    }


    public function postlike(Request $request)
    {

        $input = $request->all();

        $userId = session('loginId');

        $data = [
            'post_id' => isset($input['cid']) ? $input['cid'] : "",
            'user_id' => $userId,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $is_already_like = DB::table('tbl_like_post')->where('post_id', $data['post_id'])
            ->where('user_id', $userId)->first();
        if ($is_already_like) {

            DB::table('tbl_like_post')->where('id', $is_already_like->id)
                ->delete();
            return response()->json(['result' => "false", "msg" => "Disliked Successfully"]);
        } else {
            $insertData = DB::table('tbl_like_post')->insertGetId($data);
            return response()->json(['result' => "true", "msg" => "Liked Successfully"]);
        }

    }

    
    public function reply(Request $request)
    {

        $data = [
            'comment' => $request['comment'],
            'user_id' => session('loginId'),
            'post_id' => isset($request['post_id']) ? $request['post_id'] : '',
            'parrent_id' => $request['comment_id'],
            'created_at'=>date('Y-m-d H:i:s')
        ];
        // dd($data);

        DB::table('tbl_comments')->insert($data);

        return back();
    }


}
