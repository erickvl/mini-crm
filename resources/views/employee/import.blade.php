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

                <form id="importEmployeeForm" role="form" enctype="multipart/form-data" action="{{ route('admin.employees.import.save') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="card-body">
                        <div class="form-group">
                            <label for="companyLogo">CSV/XLSX File</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="import_file" class="custom-file-input" id="companyLogo">
                                    <label class="custom-file-label" for="companyLogo">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="">Upload</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
        </div>
    </div>
</div>
@endsection