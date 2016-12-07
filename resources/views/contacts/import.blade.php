@extends('layouts.app')

@section('content')

<div class="panel panel-default">

    <div class="panel-heading">Import <a href="{{ url('contacts/create') }}" class="pull-right"><i class="fa fa-pencil"></i> Create contact</a></div>

    <div class="panel-body">

        @if(!empty($contacts))

            @include('contacts.partials.import_vendor_contacts', compact('contacts'))
    
        @else

            @include('contacts.partials.import_csv')

        @endif

        
    </div> <!-- .panel-body -->
</div>
@endsection

