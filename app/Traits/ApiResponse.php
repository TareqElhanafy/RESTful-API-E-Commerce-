<?php
namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

trait ApiResponse {
 
    private function successResponse ($data,$code) {
 return response()->json(['data'=>$data,'code'=>$code]);
    }
 
    protected function errorResponse($error,$code){
        return response()->json(['error'=>$error,'code'=>$code]);
    }
    protected function showMessage($message,$code){
        return response()->json(['message'=>$message,'code'=>$code]);
    }
    protected function showAll(Collection $collection,$code=200){
        $collection=$this->paginate($collection);
        return $this->successResponse($collection,$code);
    }
    protected function showOne(Model $model,$code=200){
        return $this->successResponse($model,$code);
    }

    protected function paginate(Collection $collection){
        $rules=[
            'per_page'=>'integer|min:2|max:10'
        ];
        Validator::validate(request()->all(),$rules);
        $page=LengthAwarePaginator::resolveCurrentPage();

        $per_page=7;
        if (request()->has('per_page')) {
            $per_page=request()->per_page;
        }
        $results=$collection->slice(($page-1)*$per_page,$per_page);

        $paginated=new LengthAwarePaginator($results,$collection->count(),$per_page,$page,[
            'path'=>LengthAwarePaginator::resolveCurrentPath()
        ]);

        $paginated->appends(request()->all());

        return $paginated;

    }
}