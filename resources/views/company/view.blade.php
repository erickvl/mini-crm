@extends('layouts.main-app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center">
                <p><img src="{{ asset('images/' . $company->logo ) }}" class="img-size-100 img-circle mr-3"></p>
                
                <h4><strong>{{ $company->name }}</strong></h4>

                <p><a href="mailto:{{ $company->email }}">{{ $company->email }}</a></p>

                <p><a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a></p>
            </div>
        </div>
    </div>
</div>
@endsection