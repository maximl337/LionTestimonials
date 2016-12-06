<div class="row">

    <div class="col-md-12">
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
    </div>

</div>