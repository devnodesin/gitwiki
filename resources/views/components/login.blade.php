@if (Session::has('error'))
    <div class="p-2 w-100 m-auto" style="max-width: 330px; padding-top: 10% !important;">
        <div class="alert alert-danger" role="alert">
            {{ Session::get('error') }}
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="p-2 w-100 m-auto" style="max-width: 330px; padding-top: 10% !important;">
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    </div>
@endif

<div class="w-100 m-auto" style="max-width: 330px;">
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="input-group mb-3">
            <span class="input-group-text" id="id-email"><i class="bi bi-person-vcard"></i></span>
            <input type="text" name="email" class="form-control" placeholder="email">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="id-password"><i class="bi bi-asterisk"></i></span>
            <input type="password" name="password" class="form-control" placeholder="------">
        </div>

        <div class="form-check form-switch mb-3">
            <input name="remember" class="form-check-input" type="checkbox" role="switch" value="1">
            <label class="form-check-label">Keep Logged In</label>
        </div>

        <div>
            <button type="submit" class="btn btn-outline-primary mb-3">Submit</button>
        </div>
    </form>
</div>
