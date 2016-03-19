@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">

                <div class="panel-heading">Contacts <a href="{{ url('contacts/create') }}" class="pull-right"><i class="fa fa-pencil"></i> Create</a></div>

                <div class="panel-body">

                    @if(count($contacts) == 0)
                        
                        <p class="alert alert-warning">You have not invited any clients yet.</p>
                        
                    @else

                        <div class="row">
                            <div class="col-md-12">
                                @foreach($contacts->chunk(3) as $contactRow)

                                    <div class="row contacts">
                                        @foreach($contactRow as $contact) 

                                            <div class="col-md-4">
                                                <p>Name: {{ $contact->first_name . ' ' . $contact->last_name }}</p>
                                                <p>Email: {{ $contact->email }}</p>
                                                <p>Phone: {{ $contact->phone }}</p>
                                            </div>
                    
                                        @endforeach
                                    </div> <!-- .row -->

                                @endforeach
                            </div> <!-- .col -->
                        </div><!-- .row -->

                    @endif
                    

                    {!! $contacts->render() !!}
                    
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
@endsection
