@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">

                <div class="panel-heading">Import CSV <a href="{{ url('contacts/create') }}" class="pull-right"><i class="fa fa-pencil"></i> Create contact</a></div>

                <div class="panel-body">
                    
                    <form enctype="multipart/form-data" id="import_csv_form" method="POST" action="{{ url('contacts/import') }}" role="form">

                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('csv') ? ' has-error' : '' }}">
                            <label for="csv">CSV</label>
                            <span class="help-block">
                                    <strong>Import a csv with the following headers: 'firstname', 'lastname', 'email', 'phone' (optional)</strong>
                                </span>
                            <input id="csv" class="form-control " type="file" name="csv" value="{{ old('csv') }}" />
                            @if ($errors->has('csv'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('csv') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('csv') ? ' has-error' : '' }}">
                            {!! Recaptcha::render() !!}
                            @if ($errors->has('g-recaptcha-response'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <input type="submit" value="Upload" class="form-control btn btn-primary">
                        </div>
                    </form>
                    
                </div> <!-- .panel-body -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')

@if(Session::has('success'))
<script type="text/javascript">
    
    swal("Good job!", "{{ Session::get('success') }}", "success");

</script>
@endif

@if(Session::has('error'))
<script type="text/javascript">
    
    swal("Uh oh!", "{{ Session::get('error') }}", "error");

</script>
@endif
@endsection


