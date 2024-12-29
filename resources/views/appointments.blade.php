@extends('master')

@section('content')

<div class="page-heading">
    <div class="flexbox mb-4">
        <div class="flexbox">
            <h1 class="page-title">المواعيد</h1>
        </div>
    </div>
</div>
<div class="page-content fade-in-up">
<div class="ibox">
    <div class="ibox-body">
        <h4 class="font-strong mb-4">جدول المواعيد</h4>

        <div class="row mb-4">
            <div class="col-md-1">
                <label class="mb-0 mr-2">حالة الموعد:</label>
                <select class="form-control" id="status-filter" title="اختر" data-style="btn-solid" data-width="150px">
                    <option value="">الكل</option>
                    <option value="0">قيد المعالجة</option>
                    <option value="1">تم الإنجاز</option>
                </select>
            </div>

            <div class="col-md-1">
                <label class="mb-0 mr-2">نوع الموعد:</label>
                <select class="form-control" id="type-filter" title="اختر" data-style="btn-solid" data-width="150px">
                    <option value="">الكل</option>
                    <option value="receive">استلام الجواز</option>
                    <option value="info">استفسار</option>
                </select>
            </div>

            <div class="col-md-8">
            </div>

            <div class="col-md-2">
                <div class="input-group-icon input-group-icon-left mr-3">
                    <span class="input-icon input-icon-left font-16"><i class="fa fa-search"></i></span>
                    <input class="form-control form-control-rounded form-control-solid" id="key-search" type="text" placeholder="بحث...">
                </div>
            </div>
        </div>

                

        

        <div class="row">
            <div class="col-md-4">
                <div class="input-group mb-3">
                    <span class="input-group-text" >من تاريخ</span>
                    <input type="date" class="form-control" id="startdate">
                  </div>
            </div>
            <div class="col-md-4">
                <div class="input-group mb-3">
                    <span class="input-group-text">حتى تاريخ</span>
                    <input type="date" class="form-control" id="enddate">
                  </div>
            </div>
        </div>
        <div class="table-responsive row">
            <table class="table table-hover" id="table">
                <thead class="thead-default thead-lg">
                    <tr>
                        <th class="no-sort">رقم الإشعار</th>
                        <th class="no-sort">اسم العميل</th>
                        <th class="no-sort">رقم العميل</th>
                        <th class="no-sort">حالة الموعد</th>
                        <th class="no-sort">نوع الموعد</th>
                        <th class="no-sort">من تاريخ</th>
                        <th class="no-sort">حتى تاريخ</th>
                        <th class="no-sort">تاريخ إنشاء الموعد</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
</div>
@endsection

@section('js')


<script>

    $(function() {
        var today = new Date();
        var nextMonth = new Date();

        nextMonth.setDate(today.getDate() + 30);


        $('#startdate').val(today.toJSON().slice(0,10));
        $('#enddate').val(nextMonth.toJSON().slice(0,10));
        
        var table = $('#table').DataTable({
            pageLength: 100,
            responsive: true,
            fixedHeader: true,
            processing: true,
            serverSide: true,
            ajax: {
                    "url": '/api/appointments-table',
                    "type": "POST",
                    data: function (d) {
                        d.startdate = $('#startdate').val();
                        d.enddate = $('#enddate').val();
                
                    },
                },
            dom: 'rtip',
            columnDefs: [{
                targets: 'no-sort',
                orderable: false,
            },
            {
                targets: [2, 5, 6, 7],
                createdCell: function (td, cellData) {
                    $(td).css("direction", "ltr"); 
                }
            }],

            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json"
            },

            columns: [
                {data: 'id'},
                {data : 'name'},
                {data : 'phone', 
                    render: function (data) {
                        return '<span style="direction:ltr">'+ data +'</span>';
                    }
                },
                {data : 'status', 
                    render: function (data) {
                        return data == 1 ? '<span class="badge bg-success rounded-pill">تم الإنجاز</span>' :  (data == 0 ? '<span class="badge bg-warning rounded-pill">قيد المعالجة</span>' : '-');
                    }
                },
                {data : 'type', 
                    render: function (data) {
                        return data == 'receive' ? '<span class="badge bg-primary rounded-pill">استلام الجواز</span>' : (data == 'info' ? '<span class="badge bg-info rounded-pill">استفسار</span>' : '-');
                    }
                },
                {data : 'start_appointment_date'},
                {data : 'end_appointment_date'},
                {data : 'creation_date', 
                    render: function (data) {
                        const utcDate = new Date(data);

                        // Extract the local date components
                        const year = utcDate.getFullYear();
                        const month = String(utcDate.getMonth() + 1).padStart(2, "0"); // Months are zero-based
                        const day = String(utcDate.getDate()).padStart(2, "0");
                        const hours = String(utcDate.getHours()).padStart(2, "0");
                        const minutes = String(utcDate.getMinutes()).padStart(2, "0");

                        // Combine into desired format
                        const localDateString = `${year}-${month}-${day} ${hours}:${minutes}`;
                        return localDateString;
                    }
                },
            ],

            select: true,
        });
        
        $('#startdate, #enddate').change(function () {
            table.draw();
        });

        $('#key-search').on('keyup', function() {
            table.search(this.value).draw();
        });
        $('#status-filter').on('change', function() {
            table.column(3).search($(this).val()).draw();
        });
        $('#type-filter').on('change', function() {
            table.column(4).search($(this).val()).draw();
        });
        
        
    });


  
    </script>

@endsection

