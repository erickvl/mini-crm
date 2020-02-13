@extends('layouts.main-app')

@section('content')

<div class="row">
    <!-- /.col -->
    <div class="col-md-12">
      <div class="card card-primary card-outline">
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

        <div class="card-header">
          <h3 class="card-title">Compose New Message</h3>
        </div>
        <!-- /.card-header -->
        <form id="emailForm" role="form" action="{{ route('emails.send') }}" method="POST">
          {{ csrf_field() }}
          <div class="card-body">
            <div class="form-group">
              <input class="form-control" placeholder="To:" name="receiver" required>
            </div>
            <div class="form-group">
              <input class="form-control" placeholder="Subject:" name="subject" required>
            </div>
            <div class="form-group">
              <input type="text" class="form-control pull-right" id="datepicker" readonly="readonly" placeholder="Schedule Email (leave blank for immediate email sending)" name="email_schedule">
            </div>
            <div class="form-group">
                <textarea id="compose-textarea" class="form-control" style="height: 300px" name="message" required></textarea>
            </div>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <div class="float-right">
              {{-- <button type="button" class="btn btn-default"><i class="fas fa-pencil-alt"></i> Draft</button> --}}
              <button type="submit" class="btn btn-primary"><i class="far fa-envelope"></i> Send</button>
            </div>
            {{-- <button type="reset" class="btn btn-default"><i class="fas fa-times"></i> Discard</button> --}}
          </div>
          <!-- /.card-footer -->
        </form>
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
    
@endsection

@section('javascript')
    <script>
      $(function() {
        //Date picker
        $('#datepicker').datepicker({
          autoclose: true,
          startDate: new Date()
        })
      });
    </script>
@endsection