<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Core\Singleton;
use App\Http\Services\AppointmentService;
use App\Models\Enum\ResponseStatusEnum;
use App\Models\ResponseModel;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;

class AppointmentApiController extends Controller
{
    
    private $createRules;
    private $detailsRules;
    private $appointmentServices;

    public function __construct()
    {

        $this->createRules = [
            'name' => 'required',
            'phone' => ['required', new PhoneNumber],
            'type' => 'required',
        ];

        $this->detailsRules = [
            'phone' => ['required', new PhoneNumber],
        ];


        $this->appointmentServices = Singleton::Create(AppointmentService::class);


    }

    public function create(Request $request){

        $validator = validator($request->all(), $this->createRules);
        if($validator->fails()){
            $fatalData = new ResponseModel(ResponseStatusEnum::Failed, "There are some errors in the request", $validator->errors());
            return $fatalData->toJson();
        }

        $response = $this->appointmentServices->create($request->name, $request->phone, $request->type);

        return $response->toJson();

    }

    public function getLastAppointmentByPhone(Request $request){
        $validator = validator($request->all(), $this->detailsRules);
        if($validator->fails()){
            $fatalData = new ResponseModel(ResponseStatusEnum::Failed, "There are some errors in the request", $validator->errors());
            return $fatalData->toJson();
        }

        $response = $this->appointmentServices->getLastAppointmentByPhone($request->phone);

        return $response->toJson();
    }


    
}
