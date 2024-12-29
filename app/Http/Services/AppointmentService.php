<?php

namespace App\Http\Services;

use App\Models\Appointment;
use App\Models\Enum\AppointmentStatusEnum;
use App\Models\DefaultValue;
use App\Models\Enum\ResponseStatusEnum;
use App\Models\ResponseModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AppointmentService{
    #Fields


    private $startAppointmentDate = null;
    private $endAppointmentDate = null;
        
    #


    #Ctor

    public function __construct(){

    }

    #


    # Method

    public function create($name, $phone, $type){

        // check if there is another appointment
        $appointments = $this->getAppointmentsByPhone($phone, true);

        if($appointments->count() > 0){

            $firstAppointment = $appointments->first();
            return new ResponseModel(ResponseStatusEnum::Pending , 
                                    "You cannot create a new task because there is still a pending task",
                                    $this->addNewProperties($firstAppointment)
                                );
        }

        // End check...


        $clientsPerHour = DefaultValue::ClientsPerDay / DefaultValue::NumberOfWorkHours;
        
        $this->initDate(Carbon::now()->addDay()->setTime(DefaultValue::StartWorkHour, 0, 0));

        $lastAppointment = Appointment::where("type", "=", $type)->latest()->first();
        if($lastAppointment){
            
            $this->startAppointmentDate = Carbon::parse($lastAppointment->start_appointment_date);
            $this->endAppointmentDate = Carbon::parse($lastAppointment->end_appointment_date);

            $clientCountPerHourPart = Appointment::where("start_appointment_date", "=", $lastAppointment->start_appointment_date)->count();
            if($clientCountPerHourPart >= $clientsPerHour / DefaultValue::NumberOfHourParts){
                $this->startAppointmentDate = Carbon::parse($lastAppointment->end_appointment_date);
                $this->endAppointmentDate = Carbon::parse($lastAppointment->end_appointment_date)
                                                ->addMinutes( 60 / DefaultValue::NumberOfHourParts);

                if ($this->startAppointmentDate->hour >= DefaultValue::EndWorkHour) {
                    // If the time is 3 PM or later, move the date to tomorrow
                    $this->initDate(Carbon::parse($this->startAppointmentDate)
                                            ->addDay()
                                            ->settime(DefaultValue::StartWorkHour, 0, 0)
                                        );
                }
            }

        }

        $newAppointment = Appointment::create([
            'name' => $name,
            'phone' => $phone,
            'type' => $type,
            'status' => AppointmentStatusEnum::Pending,
            'creation_date' => now(),
            'start_appointment_date' => $this->startAppointmentDate->format("Y-m-d H:i"),
            'end_appointment_date' => $this->endAppointmentDate->format("Y-m-d H:i"),
        ]);

        $newAppointment = $this->addNewProperties($newAppointment);

        return new ResponseModel(ResponseStatusEnum::Success , "Successfull", $newAppointment);
    }

    public function getLastAppointmentByPhone($phone){
        $appointments = $this->getAppointmentsByPhone($phone, false);
        if($appointments->count() > 0){
            return new ResponseModel(ResponseStatusEnum::Success , 
                                    "Successfull", 
                                    $this->addNewProperties($appointments->latest()->first())
                                );
        }

        return new ResponseModel(ResponseStatusEnum::NotFound , "Not Found");

    }


    public function convertAllPendingAppointmentsToDone(){
        $now = Carbon::now();

        Appointment::where('status', '=', 0)
                    ->where('end_appointment_date', '<', $now)
                    ->update(['status' => 1]);
    }

    #


    #Uilities


    private function initDate($startDate){
        $this->startAppointmentDate = $startDate;

        if ($this->startAppointmentDate->dayOfWeek === Carbon::FRIDAY) {
            // If it's Friday, add 2 days (move to Sunday)
            $this->startAppointmentDate->addDays(2);
        } elseif ($this->startAppointmentDate->dayOfWeek === Carbon::SATURDAY) {
            // If it's Saturday, add 1 day (move to Sunday)
            $this->startAppointmentDate->addDay();
        }


        $this->endAppointmentDate = Carbon::parse($this->startAppointmentDate)
                                        ->addMinutes(60 / DefaultValue::NumberOfHourParts);

    }


    public function generate($id)
    {
        $pathfile = DefaultValue::PathDir . $id . '.png';
        $path = public_path($pathfile);
        QrCode::format('png')->size(300)->generate(secure_url('/qr/details/'. $id), $path);

        return secure_asset(URL::to('/' . $pathfile));
    }


    private function getAppointmentsByPhone($phone, $isPanding){

        $appointments = Appointment::where('phone', '=', $phone)
                                    ->where(function ($query) use ($isPanding) {
                                        if($isPanding){
                                            $query->where('status', '=', AppointmentStatusEnum::Pending)
                                                                ->orwhere('start_appointment_date', '>', Carbon::now()->format("Y-m-d H:i"));
                                        }
                                    });

        return $appointments;
    }


    private function addNewProperties($appointment){
        
        $appointment->date = Carbon::parse($appointment->start_appointment_date)->format("Y-m-d");
        $appointment->from = Carbon::parse($appointment->start_appointment_date)->format("H:i");
        $appointment->to = Carbon::parse($appointment->end_appointment_date)->format("H:i");

        $appointment->qrcode = $this->generate($appointment->id);

        return $appointment;

    }

    #
}