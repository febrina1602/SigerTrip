<form method="POST" action="{{ route('password.verifyCode') }}">
    @csrf
    <div class="form-group">
        <label for="verification_code">Kode Verifikasi</label>
        <input type="text" name="verification_code" id="verification_code" required class="form-control">
    </div>

    @error('verification_code')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

    <button type="submit" class="btn btn-primary">Verifikasi</button>
</form>
