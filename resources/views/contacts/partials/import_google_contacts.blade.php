<div class="row">

    <div class="col-md-12">
        <form id="import_google_contacts" method="POST" action="{{ url('contacts/import') }}" role="form">

            {!! csrf_field() !!}
            
            @foreach($google_contacts as $contact)
                <div class="checkbox">
                    <label>
                        <input name="contacts[]" type="checkbox"> {{ $contact['email'] }}
                    </label>
                </div>
            @endforeach
        </form>
    </div>

</div>