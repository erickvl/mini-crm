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
          <a href="{{ route('companies.add') }}" class="edit btn btn-primary btn-sm float-right">Add New</a>
        </div>
      <!-- /.card-header -->
        <div class="card-body">
          <table class="table table-bordered data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Website</th>
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
                ajax: "{{ route('companies') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'website', name: 'website'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
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