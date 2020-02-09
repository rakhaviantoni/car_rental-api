<?php

namespace App\Http\Controllers;
use Firebase\JWT\JWT;
use Illuminate\Http\Request; 

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function response($message='Success', $data = array(),$statusCode = 200){
        $res_data =array(
            'status' => $statusCode,
            'message' => $message,
            'data'=> $data,
            'meta' => null
        );
        return response()->json($res_data,$statusCode);
    }

    public function responsePaginate($message='Success', $data = array(),$statusCode = 200){
        $data = $data->toArray();
        $datax = $data['data'];
        $meta = array(
            'totalRecords' => $data['total'],
            'per_page' => (int)$data['per_page'],
            'current_page' => $data['current_page'],
            'last_page' => $data['last_page']
        );
        $res_data =array(
            'status' => $statusCode,
            'message' => $message,
            'data'=> $datax,
            'meta' => $meta
        );
        return response()->json($res_data,$statusCode);
    }
}
