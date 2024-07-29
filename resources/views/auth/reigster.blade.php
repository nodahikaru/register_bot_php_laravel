<!-- resources/views/auth/register.blade.php -->
<form method="POST" action="{{ route('register') }}">
  @csrf
  <div class="form-group">
    {!! NoCaptcha::renderJs() !!}
    {!! NoCaptcha::display() !!}
    @error('g-recaptch-response')
      <span class="text-danger">{{ $message }}</span>
    @enderror
  </div>
  <button type="submit" class="btn btn-primary">Register</button>
</form>