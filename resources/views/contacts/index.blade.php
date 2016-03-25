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

                    @if(is_null(Auth::user()->verified_at))

                        <p class="alert alert-warning">Verify your account to invite contacts</p>

                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            @foreach($contacts->chunk(3) as $contactRow)

                                <div class="row contacts">
                                    @foreach($contactRow as $contact) 

                                        <div class="col-md-4">
                                            <div class="panel panel-default">

                                                <div class="panel-body">

                                                    <p>Name: {{ $contact->first_name . ' ' . $contact->last_name }}</p>
                                            
                                                    <p>Email: {{ $contact->email }}</p>
                                                    
                                                    @if(!empty($contact->phone))
                                                        <p>Phone: {{ $contact->phone }}</p>
                                                    @endif
                                                    
                                                    <p>Invitations</p>

                                                    <p>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary dropdown-toggle {{ is_null(Auth::user()->verified_at) ? ' disabled' : '' }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Invite <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                        <li>
                                                            @if(!$contact->email_sent_at)
                                                                <a class="" href="{{ url('contacts/'.$contact->id.'/email') }}">Send Email</a>
                                                            @else
                                                                <a href="#"><span class="text-muted">Email sent</span></a>
                                                            @endif
                                                        </li>
                                                        @if(!empty($contact->phone))
                                                            <li>
                                                                @if(!$contact->sms_sent_at)
                                                                    <a href="{{ url('contacts/'.$contact->id.'/sms') }}">Send SMS</a>
                                                                @else
                                                                    <a href="#"><span class="text-muted">SMS sent</span></a>
                                                                @endif
                                                            </li>
                                                        @endif
                                                        <li role="separator" class="divider"></li>
                                                        <li><a href="#">Send Custom links</a></li>
                                                        </ul>
                                                    </div>

                                                    <!-- @if(!$contact->email_sent_at)
                                                        <a href="#" class="btn btn-small btn-primary {{ is_null(Auth::user()->verified_at) ? ' disabled' : '' }}">Email</a>
                                                    @else
                                                        <a href="#" class="btn btn-small btn-success disabled">Email Sent</a>
                                                    @endif

                                                    @if(!$contact->sms_sent_at)
                                                        <a href="#" class="btn btn-small btn-primary {{ is_null(Auth::user()->verified_at) ? ' disabled' : '' }}">SMS</a>
                                                    @else
                                                        <a href="#" class="btn btn-small btn-success disabled">SMS sent</a>
                                                    @endif -->
                                                    </p>
                                                    
                                                </div> <!-- /.panel-body -->
                                                
                                            </div> <!-- /.panel -->

                                            
                                        </div> <!-- /.col-md-4 -->
                
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
