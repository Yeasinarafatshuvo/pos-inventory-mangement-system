<?php

namespace App\Http\Controllers;

use App\Models\Bookinglist;
use App\Models\CombinedOrder;
use App\Models\DeliverymanList;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BookingController extends Controller
{
  public function index(){

    $deliverymanlist =  DeliverymanList::get();
    $product_code = CombinedOrder::get();

    return view('delivery_booking.booking', compact('product_code','deliverymanlist'));

  }

  public function store(Request $request){
    //dd($request);
    $bookinglist =  Bookinglist::create([

        'delivery_man' => $request->delivery_man,
        'order_id' => $request->order_id,
        'date' => $request->date,
        'slot' => $request->slot,

    ]);

    return redirect(RouteServiceProvider::booking_list);

  }
  public function bookList(){

    $book_list = Bookinglist::get();
    return view('delivery_booking.Booking_list', compact('book_list'));

  }

  public function edit($id=null){
    $deliverymanlist =  DeliverymanList::get();
    $product_code = DB::table('combined_orders')->get();
    $book_list = Bookinglist::get();
    $editData = Bookinglist::find($id);

    return view('delivery_booking.edit_data',compact('product_code','deliverymanlist','editData','book_list'));
  }

  public function updatedata(Request $request, $id){
    //dd($request);

       $bookinglist =  Bookinglist::find($id);
     //dd($bookinglist);

       $bookinglist->delivery_man= $request->delivery_man;
       $bookinglist->date= $request->date;
       $bookinglist->order_id= $request->order_id;
       $bookinglist->slot= $request->slot;
       //all are save
       $bookinglist->save();

       return redirect(RouteServiceProvider::booking_list);

    }



    public function delete($id)
    {
        $delete = Bookinglist::where('id', $id)->delete();
        if ($delete == 1) {
            $success = true;
            $message = "User deleted successfully";
        } else {
            $success = true;
            $message = "User not found";
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);


    }

  //For ajax data
  public function getUserData($id=null){
    $deliverymanData = DeliverymanList::get();
    $product_code = DB::table('combined_orders')->get();
    $ajaxbooklist = Bookinglist::get();
    $editData = Bookinglist::get();



    return json_encode(array('data'=>$deliverymanData,'data2'=>$product_code, 'data3'=>$ajaxbooklist, 'data4'=>$editData));
  }


}
