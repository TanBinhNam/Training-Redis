@extends('layout')
@push('css')
<style>
    .container{
        margin-top:300px;
    }
    .input-icon{
      size:20px;
  position: absolute  ;
  left: 7px  ;
  top: 5px;
}
input{
  padding-left: 35px  !important;
}
.input-wrapper{
  position: relative;
}
</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5" >
      <div class="col-md-6">
        @if(isset($err))
        <div class="alert alert-danger" role="alert">
          {{ $err }}
        </div>
        @endif
        
        <form method="POST" action="">
    
          @csrf
          <div class="form-group mt-3  input-wrapper">
   
            <input type="email" class="form-control" id="email" name="email" value="{{ session('email') ?? old('email') }}" placeholder="Email" required>
            <label for="email" class="fa fa-user input-icon fa-2x"></label>

            @if (session('err-email'))
            <div style="color: red">
              {{ session('err-email') }}
            </div>
            @endif
            @error('email')
            <div style="color: red">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-group input-wrapper">
        
            <input type="password" class="form-control" name="password" id="password" value="{{ old('password') }}" placeholder="Mật khẩu" required>
            <label for="password" class="fa fa-lock  input-icon fa-2x"></label>
            @if (session('err-password'))
            <div style="color: red">
              {{ session('err-password') }}
            </div>
            @endif
            @error('password')
            <div style="color: red">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-inline">

          <div class="form-check mr-auto">
            <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">Remember</label>
          </div>

          <button type="submit" class="btn btn-primary">Đăng nhập</button>
        </div>

        </div>
        </form>
        <br>
      </div>
    </div>
  </div>
@endsection