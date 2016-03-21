@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">

                <div class="panel-heading">Profile <a href="{{ url('profile/edit') }}" class="pull-right"><i class="fa fa-pencil"></i> Edit</a></div>

                <div class="panel-body">

                    @if(!is_null($user->verified_at))

                        <p><span class="label label-success">Verified</span></p>
                    @endif

                    <p>Name: {{ $user->first_name . ' ' . $user->last_name }}</p>

                    <p>Email: {{ $user->email }}</p>
                    
                    
                    
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

    //console.log('has message');

</script>
@endif

@if(Session::has('error'))
<script type="text/javascript">
    
    swal("Uh oh!", "{{ Session::get('error') }}", "error");

    //console.log('has message');

</script>
@endif
@endsection

