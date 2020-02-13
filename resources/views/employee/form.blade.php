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
                
                @if ( Auth::guard('web')->check() )
                    <form id="employeeForm" role="form" action="{{ $action == 'add' ? route('admin.employees.save') : route('admin.employees.update') }}" method="POST">
                @else
                    <form id="employeeForm" role="form" action="{{ route('employees.update') }}" method="POST">
                @endif
                    {{ csrf_field() }}
                    <div class="card-body">
                        @if ( $employee )
                            <input type="hidden" readonly name="id" class="form-control" id="employeeId" value="{{ $employee->id }}">
                        @endif

                        <div class="form-group">
                            <label for="employeeFName">First Name</label>
                            <input type="text" name="first_name" class="form-control" id="employeeFName" value="{{ $employee ? $employee->first_name : old('first_name') }}" placeholder="Enter First Name" required>
                        </div>
                        <div class="form-group">
                            <label for="employeeLName">Last Name</label>
                            <input type="text" name="last_name" class="form-control" id="employeeLName" value="{{ $employee ? $employee->last_name : old('last_name') }}" placeholder="Enter Last Name" required>
                        </div>
                        <div class="form-group">
                            <label for="employeeEmail">Email address</label>
                            <input type="email" name="email" class="form-control" id="employeeEmail" value="{{ $employee ? $employee->email :  old('email') }}" placeholder="Enter email" required>
                        </div>
                        <div class="form-group">
                            <label for="employeePassword">Password</label>
                            <input type="password" name="password" class="form-control" id="employeePassword" placeholder="Enter Password" required>
                        </div>
                        <div class="form-group">
                            <label for="employeePhone">Phone</label>
                            <input type="tel" name="phone" class="form-control" id="employeePhone" value="{{ $employee ? $employee->phone :  old('phone') }}" placeholder="Enter Phone">
                        </div>
                        <div class="form-group">
                            <label>Company</label>
                            <select class="form-control select2" name="company_id" style="width: 100%;">
                                @foreach ($companies as $company)
                                    @if ( $employee )
                                        <option {{ $company->id == $employee->company->id ? 'selected' : '' }} value="{{ $company->id }}">{{ $company->name }}</option>
                                    @else
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endif
                                @endforeach
                            </select>
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