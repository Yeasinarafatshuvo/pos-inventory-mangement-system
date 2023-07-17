<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class ApiStoreAttendanceDataApiController extends Controller
{
    public function store_new_attendance_data(Request $request)
    {
       
        $data = $request->all();

         // Encode the array back into JSON format
         $json = json_encode($data);
          
         // Overwrite the contents of the data.json file with the new data
         $file = public_path('data.json');
         file_put_contents($file, $json, LOCK_EX);
         return 1;

    }
}
