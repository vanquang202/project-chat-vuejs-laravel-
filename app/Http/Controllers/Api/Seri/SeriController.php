<?php

namespace App\Http\Controllers\Api\Seri;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Seri;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SeriController extends Controller
{
    protected $seri ;
    protected $post ;
    public function __construct(Seri $seri , Post $post){
        $this -> seri = $seri;
        $this -> post = $post;
    }
    public function getSeriMe(){
        $data = $this -> seri::where('user_id' , auth() -> user() -> id ) -> with('post') -> get();
        if($data){
            return response()->json([
                'code' => Response::HTTP_OK,
                'data' => $data,
            ]);
        }else{
            return response()->json([
                'code' => Response::HTTP_BAD_GATEWAY,
            ]);
        }
    }

    public function getSeriYou($id){
        $data = $this -> seri::where('user_id' , $id ) -> with('post') -> get();
        if($data){
            return response()->json([
                'code' => Response::HTTP_OK,
                'data' => $data,
            ]);
        }else{
            return response()->json([
                'code' => Response::HTTP_BAD_GATEWAY,
            ]);
        }
    }

    public function index(){

//        $data = $this -> seri -> a_all(\request('limit') ?? 10 ,['user'],[],'desc');
//        if($data !== null){
//            return response()->json([
//                'code' => Response::HTTP_OK,
//                'data' => $data ,
//            ]);
//        }else{
//            return response()->json([
//                'code' => Response::HTTP_BAD_GATEWAY,
//            ]);
//        }
    }
    public function store(Request $request){

        $data = $this -> seri -> a_create($request -> data);
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

    public function edit($id){
        $data = $this -> seri::find($id);
        $post = $this -> post::where('user_id', auth() -> user() -> id) -> where('seri_id' , null) -> orWhere('seri_id', 0 ) ->get();
        if($data){
            return response()->json([
                'code' => Response::HTTP_OK,
                'data' => $data,
                'post' => $post,
            ]);
        }else{
            return response()->json([
                'code' => Response::HTTP_BAD_GATEWAY,
            ]);
        }
    }

    public function update(Request $request,$id){
        if(!empty($request->data_add_post)){
            $this -> post::whereIn('id' ,$request->data_add_post )->update(['seri_id' =>  $id]);
        }
        $data = $this -> seri -> a_update($request -> data , $id);
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
        $data = $this -> seri -> a_destroy($id);
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
