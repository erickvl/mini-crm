@extends('layouts.main-app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center">
                <p><img src="{{ asset('images/' . $employee->company->logo ) }}" class="img-size-100 img-circle mr-3"></p>
                
                <h4><strong>{{ $employee->first_name . ' ' . $employee->last_name }}</strong></h4>

                <p><a href="{{ Auth::guard('web')->check() ? route("admin.companies") .'/'.$employee->company->id : route("companies") .'/'.$employee->company->id }}">{{ $employee->company->name }}</a></p>

                <p><a href="mailto:{{ $employee->email }}">{{ $employee->email }}</a></p>

                <p><a href="tel:{{ $employee->phone }}">{{ $employee->phone }}</a></p>
            </div>
        </div>
    </div>
</div>
@endsection