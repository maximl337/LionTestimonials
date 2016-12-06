@extends('layouts.app')

@section('content')

<div class="panel panel-default">

    <div class="panel-heading">Import CSV <a href="{{ url('contacts/create') }}" class="pull-right"><i class="fa fa-pencil"></i> Create contact</a></div>

    <div class="panel-body">

        @if(!empty($google_contacts))

            @include('contacts.partials.import_google_contacts', ['google_contacts' => $google_contacts])
    
        @else

            @include('contacts.partials.import_csv')

        @endif

        
    </div> <!-- .panel-body -->
</div>
@endsection

