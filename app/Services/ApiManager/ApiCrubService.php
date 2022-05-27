<?php
namespace App\Services\ApiManager;

use Illuminate\Routing\Pipeline;

trait ApiCrubService
{
    public function a_all($limit = 10,$withManyQuerys = [] ,  $queryAdds = [] ,$sort = 'asc'){

        try {
            $data = app(Pipeline::class)
                ->send($this::query())
                ->through($queryAdds)
                ->thenReturn()
                ->with($withManyQuerys)
                ->orderBy('id', ($sort == 'asc' ? 'asc' : 'desc') )
                ->paginate($limit);
            return $data;
        }catch (\Throwable $th){
            return false;
        }
    }

    public function a_create($data)
    {
        try {
            if(isset($data['image'])){

                $nameImage = uniqid() .'.'. $data['image']   -> getClientOriginalExtension();
                $nameImage2 = $nameImage;
                $data['image'] -> move(public_path('images') , $nameImage );
                $data['image']  = $nameImage;

            } elseif (isset($data['images'])){

                foreach($data['images'] as $image){
                    $nameImage = uniqid() .'.'. $image   -> getClientOriginalExtension();
                    $nameImage2 = $nameImage;
                    $image -> move(public_path('images') , $nameImage );
                    $dataSave = \Arr::except($data,['images']);
                    $dataSave['image']  = $nameImage;
                    $this::create($dataSave);
                }

                return true;
            };
            $this::create($data);
            return true;
        }catch (\Throwable $th){
            return false;
        }
    }

    public  function  a_show($id,$withManyQuerys = [])
    {
        try {
            $data = $this::find($id)->load($withManyQuerys);
            return $data;
        }catch (\Throwable $th){
            return false;
        }

    }

    public function a_update($data,$id)
    {
        try {
            $model = $this::find($id) ;

            // foreach($data as $k => $v)
            // {  

            //Data save new clean code  1
            // $model -> { $k } = $model 
            //                 -> getColumn()[$k] 
            //                 -> setValue($v) 
            //                 -> execute() ;

            // Data save new clean code 2
            //     switch($k)
            //     {
            //         case 'image':
            //             $nameImage = uniqid() .'.'. $data['image']   -> getClientOriginalExtension();
            //             $data['image'] -> move(public_path('images') , $nameImage ); 
            //             if(file_exists( public_path('images').'\\'. $data -> image)){
            //                 unlink(public_path('images').'\\'. $data -> image);
            //             }
            //             $model -> { $k } = $nameImage ;
            //             break;
            //         default:
            //         $model -> { $k } = $v ;
            //     }
            // }
            // $model -> save();

            if(isset($data['image'])  ){
                $nameImage = uniqid() .'.'. $data['image']   -> getClientOriginalExtension();
                $data['image'] -> move(public_path('images') , $nameImage );
                $data['image']  = $nameImage;
                if(file_exists( public_path('images').'\\'. $data -> image)){
                    unlink(public_path('images').'\\'. $data -> image);
                }
            };

            $model  -> update($data );
            return true;
        }catch (\Throwable $th){
            return false;
        }
    }

    public function a_destroy($id)
    {
        try {
            $data = $this::find($id);
            if(isset($data -> image)){
                if(file_exists( public_path('images').'\\'. $data -> image)){
                    unlink(public_path('images').'\\'. $data -> image);
                };
            };
            $data -> delete();
            return true;
        }catch (\Throwable $th){
            return false;
        }
    }
}
