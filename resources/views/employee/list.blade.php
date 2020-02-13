@extends('layouts.main-app')

@section('content')

  <div class="row">
    <div class="card card-warning card-outline" style="display: none;">

      <div class="card-body">
        <button type="button" class="btn btn-success toastrDefaultSuccess">
        </button>
        <button type="button" class="btn btn-danger toastrDefaultError">
        </button>
      </div>
    <!-- /.card -->
    </div>
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          {{-- <h3 class="card-title">DataTable with minimal features & hover style</h3> --}}
          @if ( Auth::guard('web')->check() )
            <div class="form-group float-left">
              <select class="form-control select2" id="export_company_id" style="width: 100%;">
                  <option selected value="">Select Company</option>
                  @foreach ($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                  @endforeach
              </select>
            </div>

            <a href="{{ route('admin.employees.add') }}" class="edit btn btn-primary btn-sm float-right">Add New</a>
            <a href="{{ route('admin.employees.import') }}" class="edit btn btn-primary btn-sm float-right mr-2" id="export-csv">Import</a>
            <a href="{{ route('admin.employees.export') .'/csv' }}" class="edit btn btn-success btn-sm float-right mr-2" id="export-csv">Export CSV</a>
            <a href="{{ route('admin.employees.export') .'/xlsx' }}" class="edit btn btn-success btn-sm float-right mr-2" id="export-xlsx">Export XLSX</a>
          @else
            <a href="{{ route('employees.edit', Auth::guard('employee')->id()) }}" class="edit btn btn-primary btn-sm float-right">Edit Profile</a>
          @endif
        </div>
      <!-- /.card-header -->
        <div class="card-body" id="transaction_wrapper">
          <table class="table table-bordered data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Company</th>
                <th width="100px">Actions</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
    
@endsection


@section('javascript')
    <script>
        $(function () {
            
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ Auth::guard('web')->check() ? route('admin.employees') : route('employees') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'full_name', name: 'full_name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'company', name: 'company'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            $(this).on('change', '#export_company_id', function() {
              var companyId = $(this).val();

              // var csvLink = $("#export-csv").attr('href');
              // var xlxsLink = $("#export-xlxs").attr('href');
              
              $("#export-csv").attr('href', '{{ Auth::guard("web")->check() ? route("admin.employees.export") : "" }}/csv/' + companyId);
              $("#export-xlsx").attr('href', '{{ Auth::guard("web")->check() ? route("admin.employees.export") : "" }}/xlsx/' + companyId);

            });

            var message = "{{ session('success') ? session('success') : '' }}";
            if ( message == 'Deleted Successfully' ) {
              $('.toastrDefaultSuccess').click();
            } else if ( message == 'Error' ) {
              $('.toastrDefaultError').click();
            }            
        });
    </script>
@endsection