<?php

namespace App\Modules\v1\Controllers;
use App\Http\Controllers\Controller;
use App\Modules\v1\Models\{Car, Reservation};
use Illuminate\Http\Request;
use Validator;
use DB;

class Cars extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(Request $request, $registration_no=null)
    {
        if(!$registration_no){
            $limit = $request->per_page ?? 10;
            $offset = $request->page ? ($limit*($request->page - 1)) : 0;
            $q = $request->q ?? '';
            $date = $request->date ?? date('Y-m-d');
            $cars = Car::query();
            $cars = $cars->select('registration_no', 'color');
            if($q){
                $cars = $cars->where('registration_no','like','%'.$q.'%')
                            ->where('color','like','%'.$q.'%');
            }
            $cars = $cars->limit($limit)
                    ->offset($offset)
                    ->paginate($limit);
            return $this->responsePaginate('',$cars,200);
        } else {
            $car = Car::query();
            $car = $car->select('registration_no', 'color')
                    ->where('registration_no', $registration_no)
                    ->first();
            if(!$car) {
                return $this->response('Car '.$registration_no.' not found', null, 400);
            }
            return $this->response('Car '.$car->registration_no.' found', $car, 200);
        }
    }

    public function status(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');
        // if(!$date){
        //    return $this->response('Date parameter needed',null,404);
        // }
        $cars = Car::get();
        $reservations = Reservation::where('date',$date)->get();
        $status = [];
        foreach ($cars as $key => $value) {
            if(count($reservations) > 0) {
                foreach ($reservations as $id => $val) {
                    if($val->registration_no == $value->registration_no) {
                        $status[$key]['registration_no'] = $value->registration_no;
                        $status[$key]['color'] = $value->color;
                        $status[$key]['status'] = 'Rented';
                        $status[$key]['customer'] = $val->customer;
                    } else {
                        $status[$key]['registration_no'] = $value->registration_no;
                        $status[$key]['color'] = $value->color;
                        $status[$key]['status'] = 'Free';
                        $status[$key]['customer'] = '';
                    }
                }
            } else {
                $status[$key]['registration_no'] = $value->registration_no;
                $status[$key]['color'] = $value->color;
                $status[$key]['status'] = 'Free';
                $status[$key]['customer'] = '';
            }
        }
        return $this->response('Cars status on '.$date.' found',$status,200);
    }

    public function create(Request $req)
    {
        $rules = array(
            'registration_no' => 'required|unique:cars|min:7',
            'color' => 'required|unique:cars'
        );
        $validator = Validator::make($req->json()->all(),$rules);
        if($validator->passes()){
            $car = new Car;
            $car->registration_no = $req->registration_no;
            $car->color = $req->color;
            $car->save();
            return $this->response('Car '.$car->registration_no.' '.$car->color.' saved', $car, 200);
        } else {
            return $this->response($validator->errors()->first(), null, 400);
        }
     }

     public function update(Request $req, $registration_no)
     { 
         $car= Car::where('registration_no', $registration_no)->first();
         if(!$car){
            return $this->response('Car not found',null,404);
         }
         if($req->registration_no) { 
            $validator = Validator::make($req->json()->all(),array('registration_no'=>'required'));
            if($validator->passes()){
                $car->registration_no = $req->registration_no;
            } else {
                return $this->response($validator->errors()->first(), null, 400);
            }
         };
         if($req->color) { 
            $validator = Validator::make($req->json()->all(),array('color'=>'required'));
            if($validator->passes()){
                $car->color = $req->color;
            } else {
                return $this->response($validator->errors()->first(), null, 400);
            }
         };
         $car->save();
         return $this->response('Car successfully updated.',$car, 200);
     }

     public function destroy($registration_no)
     {
         $car = Car::where('registration_no', $registration_no)->first();
         if(!$car){
            return $this->response('Car not found',null,404);
         }
         $car->delete();
         return $this->response('Car successfully removed.',$car, 200);
     }
    //
}
