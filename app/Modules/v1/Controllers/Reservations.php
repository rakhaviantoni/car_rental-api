<?php

namespace App\Modules\v1\Controllers;
use App\Http\Controllers\Controller;
use App\Modules\v1\Models\{Car, Reservation};
use Illuminate\Http\Request;
use Validator;
use DB;

class Reservations extends Controller
{
    public function __construct()
    {
        //
    }

    // public function index(Request $request, $id=null)
    // {
    //     if(!$id){
    //         $limit = $request->per_page ?? 10;
    //         $offset = $request->page ? ($limit*($request->page - 1)) : 0;
    //         $q = $request->q ?? '';
    //         $date = $request->date ?? date('Y-m-d');
    //         $reservations = Reservation::query();
    //         $reservations = $reservations->select('id', 'registration_no', 'customer');
    //         if($q){
    //             $reservations = $reservations->where('registration_no','like','%'.$q.'%')
    //                         ->where('customer','like','%'.$q.'%');
    //         }
    //         $reservations = $reservations->limit($limit)
    //                 ->offset($offset)
    //                 ->paginate($limit);
    //         return $this->responsePaginate('',$reservations,200);
    //     } else {
    //         $reservation = Reservation::query();
    //         $reservation = $reservation->select('id', 'registration_no', 'customer')
    //                 ->where('id',$id)
    //                 ->first();
    //         return $this->response('',$reservation,200);
    //     }
    // }

    public function reserve(Request $req)
    {
        $rules = array(
            'registration_no' => 'required|min:7',
            'customer' => 'required'
        );
        $validator = Validator::make($req->json()->all(),$rules);
        if($validator->passes()){
            $reservation = new Reservation;
            $reservation->registration_no = $req->registration_no;
            $reservation->customer = $req->customer;
            $reservation->date = $req->date ?? date('Y-m-d');
            $exist = Reservation::where('registration_no', $reservation->registration_no)
                            ->where('date', $reservation->date)
                            ->first();
            if($exist) {
                return $this->response('Already rented by '.$reservation->customer.' on '.$reservation->date, $exist, 400);
            }
            $reservation->save();
            return $this->response('Reserved '.$reservation->registration_no.' to '.$reservation->customer.' on '.$reservation->date, $reservation, 200);
        } else {
            return $this->response($validator->errors()->first(), null, 400);
        }
     }

    //  public function update(Request $req, $id)
    //  { 
    //      $reservation= Reservation::where('id', $id)->first();
    //      if(!$reservation){
    //         return $this->response('Reservation not found',null,404);
    //      }
    //      if($req->registration_no) { 
    //         $validator = Validator::make($req->json()->all(),array('registration_no'=>'required'));
    //         if($validator->passes()){
    //             $reservation->registration_no = $req->registration_no;
    //         } else {
    //             return $this->response($validator->errors()->first(), null, 400);
    //         }
    //      };
    //      if($req->customer) { 
    //         $validator = Validator::make($req->json()->all(),array('customer'=>'required'));
    //         if($validator->passes()){
    //             $reservation->customer = $req->customer;
    //         } else {
    //             return $this->response($validator->errors()->first(), null, 400);
    //         }
    //      };
    //      if($req->date) { 
    //         $validator = Validator::make($req->json()->all(),array('date'=>'required'));
    //         if($validator->passes()){
    //             $reservation->date = $req->date;
    //         } else {
    //             return $this->response($validator->errors()->first(), null, 400);
    //         }
    //      };
    //      $reservation->save();
    //      return $this->response('Reservation successfully updated', $reservation, 200);
    //  }

     public function cancel($id)
     {
         $reservation = Reservation::where('id', $id)->first();
         if(!$reservation){
            return $this->response('Reservation not found',null,404);
         }
         $reservation->delete();
         return $this->response('Reservation successfully cancelled', $reservation, 200);
     }
    //
}
