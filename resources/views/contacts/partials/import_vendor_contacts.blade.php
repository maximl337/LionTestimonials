<div class="row">

    <div class="col-md-12">
        <form id="import_google_contacts" method="POST" action="{{ url('contacts/import/vendor') }}" role="form">

            {!! csrf_field() !!}

            <div class="checkbox">
                <label>
                    <input onClick="toggle(this)" type="checkbox"> <strong>Select all</strong>
                </label>
            </div>
            <div style="height: 200px; overflow-y: auto;">
                @foreach($contacts as $contact)
                    <div class="checkbox">
                        <label>
                            <input class="contact_import" name="contact_imports[]" value="{{ $contact->id }}" type="checkbox"> {{ $contact->email }}
                        </label>
                    </div>
                @endforeach    
            </div>
            
            <div class="form-group">
                <input type="submit" class="btn btn-primary form-control" value="Save" />
            </div>
        </form>
    </div>

</div>

<script type="text/javascript">
function toggle(source) {
  var checkboxes = document.getElementsByClassName('contact_import');
    for(var i=0; i<checkboxes.length; i++) {
        checkboxes[i].checked = source.checked;
    }
    
}
</script>
