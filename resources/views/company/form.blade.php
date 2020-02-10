@extends('layouts.main-app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ( Session::has('message') && !$errors->any() )
                    <div class="alert alert-success">
                        {{ Session::get('message') }}
                    </div>
                @endif

                <form id="companyForm" role="form" action="{{ $action == 'add' ? route('companies.save') : route('companies.update') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="card-body">
                        @if ( $company )
                            <input type="text" name="id" class="form-control" id="companyId" value="{{ $company->id }}">
                        @endif

                        <div class="form-group">
                            <label for="companyName">Name</label>
                            <input type="text" name="name" class="form-control" id="companyName" value="{{ $company ? $company->name : old('name') }}" placeholder="Enter Name" required>
                        </div>
                        <div class="form-group">
                            <label for="companyEmail">Email address</label>
                            <input type="email" name="email" class="form-control" id="companyEmail" value="{{ $company ? $company->email :  old('email') }}" placeholder="Enter email" required>
                        </div>
                        <div class="form-group">
                            <label for="companyWebsite">Website</label>
                            <input type="url" name="website" class="form-control" id="companyWebsite" value="{{ $company ? $company->website :  old('website') }}" placeholder="Website" required>
                        </div>
                        <div class="form-group">
                            <label for="companyLogo">Logo</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="logo" class="custom-file-input" id="companyLogo">
                                    <label class="custom-file-label" for="companyLogo">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="">Upload</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    
                    @if ( $action != 'view' )
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    @endif
                </form>
        </div>
    </div>
</div>
@endsection