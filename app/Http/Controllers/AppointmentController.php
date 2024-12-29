<?php

namespace App\Http\Controllers;

use App\Http\Services\AppointmentService;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function test(Request $request){
        $s = new AppointmentService();
        $s->convertAllPendingAppointmentsToDone();

        return 'Done';
    }

    public function appointmentsView(){
        return view('appointments');
    }

    public function appointmentDetails(Request $request){

        $appointment = Appointment::find($request->id);
        if($appointment){
            return view('details', [
                                    "appointment" => $appointment,
                                ]);
        }

        return response()->view('errors.404', [], 404);

    }

    public function appointmentsTable(Request $request){

        $min = date("Y-m-d", strtotime($request->startdate));
        $max = date("Y-m-d", strtotime($request->enddate));

        $appointments = Appointment::Where('start_appointment_date', '>=', $min)
                            ->where('end_appointment_date', '<=', $max);

        $search = $request->search['value'];
        if($search != null || $search != ""){
            $appointments = $appointments->where('id', 'like', '%' . $search . '%')
                                        ->orwhere('name', 'like', '%' . $search . '%')
                                        ->orwhere('phone', 'like', '%' . $search . '%');
        }


        $searchStatus = $request->columns[3]['search']['value'];
        if($searchStatus != null || $searchStatus != ""){
            $appointments = $appointments->where('status', '=', $searchStatus);
        }


        $searchType = $request->columns[4]['search']['value'];
        if($searchType != null || $searchType != ""){
            $appointments = $appointments->where('type', '=', $searchType);
        }

        $recordsFiltered = $appointments->count();

        $data = $appointments->skip($request->start)
                        ->take($request->length)
                        ->orderByDesc('creation_date')
                        ->get();

        return json_encode([
            "draw" => $request->draw,
            "recordsTotal" => Appointment::count(),
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        ]);

    }
}
