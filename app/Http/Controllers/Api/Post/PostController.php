<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Models\Follower;
use App\Models\Post;
use App\Models\Seri;
use App\Models\User;
use App\Services\Query\Search;
use App\Services\Query\Tag;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    protected $post;
    protected $seri;
    protected $user;
    protected $follower;

    public function __construct(Post $post , Seri $seri , User $user , Follower $follower){

        $this -> post = $post;
        $this -> seri = $seri;
        $this -> user = $user;
        $this -> follower = $follower;

    }

    public function increment($id){
        $list = explode(  ',' , auth() -> user() -> vote ?? '');
        if(!in_array($id, $list)){
            $post = $this -> post::find($id);
            DB::transaction(function () use (&$list, &$post, &$id) {
                $post -> increment('vote');
                $list = implode(',', $list) . ',' . (string)  $id;
                auth() -> user() -> update([
                    'vote' => $list
                ]);
            });
            return 1;
        }
        return 0;

    }

    public function decrement($id){
        $list = explode(  ',' , auth() -> user() -> vote ?? '');
        if(!in_array($id, $list)){
            $post = $this -> post::find($id);
            DB::transaction(function() use (&$post , &$list , &$id) {
                $post -> decrement('vote');

                $list = implode(',', $list) . ',' . (string)  $id;
                auth() -> user() -> update([
                    'vote' => $list
                ]);
            });
            return 1;
        }
        return 0;
    }

    public function getPostMe(){
        $id = auth() -> user()->id;
        $view = $this->post::where('user_id' , $id)->sum('view');
        $vote = $this->post::where('user_id' , $id)->sum('vote');
        $user = $this -> user::with('follower')->find($id)  ;
        $data = $this->post::where('user_id' , $id)
                            -> with('user' , 'seri')
                            -> orderBy('id','desc')
                            -> paginate(\request('limit') ?? 3);
        if($data !== null){
            return response()->json([
                'code' => Response::HTTP_OK,
                'data' => $data ,
                'sum_view' => $view,
                'sum_vote' => $vote,
                'follower' => $user -> follower -> folower ?? null,
            ]);
        }else{
            return response()->json([
                'code' => Response::HTTP_BAD_GATEWAY,
            ]);
        }
    }

    public function getPostYou($id){
        $view = $this->post::where('user_id' , $id)->sum('view');
        $vote = $this->post::where('user_id' , $id)->sum('vote');
        $data = $this->post::where('user_id' , $id)
            -> with('user' , 'seri')
            -> orderBy('id','desc')
            -> paginate(\request('limit') ?? 3);
        if($data !== null){
            return response()->json([
                'code' => Response::HTTP_OK,
                'data' => $data ,
                'sum_view' => $view,
                'sum_vote' => $vote,
            ]);
        }else{
            return response()->json([
                'code' => Response::HTTP_BAD_GATEWAY,
            ]);
        }
    }

    public function index(){
        if(request() -> has('follower')){
            $follows = $this-> follower::all()->toArray();
            $arr  = array_filter($follows , function($follower){
                return in_array(auth() -> user() -> id , $follower['folower'] ?? []) ;
            });

            $arr = array_map(function($a){
                return $a['user_id'];
            },$arr);

            $arr = (array_diff(array_unique(\Arr::flatten($arr)) , [auth() -> user() -> id]));
            $data = $this -> post::whereIn('user_id' , $arr)->with(['user' , 'seri'])->orderBy('id','desc')->paginate(\request('limit') ?? 10);

        }else{
            $data = $this -> post -> a_all(\request('limit') ?? 10 ,['user' , 'seri'],[ Tag::class , Search::class],'desc');
        }

        $limit_take_has_cahnge = 2;
        $postRank = $this -> post::latest('vote')->with('user')->take($limit_take_has_cahnge) -> get();
        $postView = $this -> post::latest('view')->with('user')->take($limit_take_has_cahnge) -> get();

        $user = $this -> user::query() -> withCount('post') -> get()  ;
        $max = $user -> max('post_count');
        $user = array_filter($user -> toArray(),function($u) use ($max){
            return  $u['post_count'] == $max;
        });

        if($data !== null){
            return response()->json([
                'code'          => Response::HTTP_OK,
                'data'          => $data ,
                'post_vote'      => $postRank,
                'post_view'     => $postView,
                'top_one'       => array_merge($user),
            ]);

        }else{
            return response()->json([
                'code' => Response::HTTP_BAD_GATEWAY,
            ]);
        }
    }
    public function show($id){

        $data = $this -> post -> a_show($id , ['user' , 'seri' , 'comment']);
        $data -> increment('view');
        if($data -> seri){
            $seri = $data -> seri -> post;
        }

        if($data !== null){
            return response()->json([
                'code' => Response::HTTP_OK,
                'data' => $data ,
                'data_sp' => $seri ?? null,
            ]);
        }else{
            return response()->json([
                'code' => Response::HTTP_BAD_GATEWAY,
            ]);
        }
    }
    public function store(Request $request){

        $data = $this -> post -> a_create($request -> data);
        if($data){
            return response()->json([
                'code' => Response::HTTP_OK,
            ]);
        }else{
            return response()->json([
                'code' => Response::HTTP_BAD_GATEWAY,
            ]);
        }
    }
    public function update(Request $request,$id){

        $data = $this -> post -> a_update($request -> data , $id);
        if($data){
            return response()->json([
                'code' => Response::HTTP_OK,
            ]);
        }else{
            return response()->json([
                'code' => Response::HTTP_BAD_GATEWAY,
            ]);
        }
    }
    public function destroy($id){
        $data = $this -> post -> a_destroy($id);
        if($data){
            return response()->json([
                'code' => Response::HTTP_OK,
            ]);
        }else{
            return response()->json([
                'code' => Response::HTTP_BAD_GATEWAY,
            ]);
        }
    }
}
