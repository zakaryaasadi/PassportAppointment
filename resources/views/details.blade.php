@extends('master')

@section('content')

<div class="page-heading">
    <div class="flexbox mb-4">
        <div class="flexbox">
            <h1 class="page-title">تفاصيل الموعد</h1>
        </div>
    </div>
</div>
<div class="page-content fade-in-up">
<div class="ibox">
    <div class="ibox-body">
        <h4 class="font-strong mb-4">التفاصيل</h4>
        <h5 class="font-strong mb-4">
            رقم الإشعار: 
            <span class="font-bold">{{$appointment->id}}</span>
        </h5>
        <h5 class="font-strong mb-4">
            اسم العميل: 
            <span class="font-bold">{{$appointment->name}}</span>
        </h5>
        <h5 class="font-strong mb-4">
            حالة الطلب:
            @if($appointment->status == 1)
                <span class="badge bg-success rounded-pill" style="font-size: 1.1rem">تم الإنجاز</span>
            @elseif($appointment->status == 0)
                <span class="badge bg-warning rounded-pill" style="font-size: 1.1rem">قيد المعالجة</span>
            @else
                <span> - </span>
            @endif
        </h5>
        <h5 class="font-strong mb-4">
            نوع الطلب:
            @if($appointment->type == "receive")
            <span class="badge bg-primary rounded-pill" style="font-size: 1.1rem">استلام الجواز</span>
            @elseif($appointment->type == "info")
                <span class="badge bg-info rounded-pill" style="font-size: 1.1rem">استفسار</span>
            @else
                <span> - </span>
            @endif
        </h5>
        <h5 class="font-strong mb-4">
            تاريخ الموعد :
            <span class="text-primary font-bold" dir="ltr">
                {{\Carbon\Carbon::parse($appointment->end_appointment_date)->format('Y-m-d')}}
            </span>
        </h5>
        <h5 class="font-strong mb-4">
             الحضور بين الساعة :
            <span class="text-primary font-bold">
                {{\Carbon\Carbon::parse($appointment->start_appointment_date)->format('H:i') . " و " . \Carbon\Carbon::parse($appointment->end_appointment_date)->format('H:i')}}
            </span>
        </h5>

    </div>
</div>
</div>

@endsection
