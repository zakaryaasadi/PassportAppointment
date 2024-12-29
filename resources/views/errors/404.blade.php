@extends('master')
@section('head')
<style>
    body {
        background-color: #fff;
        background-repeat: no-repeat;
        background-image: url({{asset('/img/icons/search-document-3.svg')}});
        background-position: 80% 0;
    }

    .error-content {
        max-width: 620px;
        margin: 200px auto 0;
    }

    .error-icon {
        height: 160px;
        width: 160px;
        background-image: url({{asset('/img/icons/search-document.svg')}});
        background-size: cover;
        background-repeat: no-repeat;
    }

    .error-code {
        font-size: 120px;
        color: #5c6bc0;
    }
</style>
@endsection
@section('content')


<div class="error-content">
    <div class="flexbox">
        <span class="error-icon"></span>
        <div class="flex-1">
            <h1 class="error-code">404</h1>
            <h3 class="font-strong">لم يتم العثور على الموعد</h3>
            <p>للأسف... لم يتم العثور على الموعد يمكنك حجز موعد جديد</p>
        </div>
    </div>

</div>
@endsection
