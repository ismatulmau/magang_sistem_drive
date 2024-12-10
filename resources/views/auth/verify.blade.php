@extends('layouts.app')

@section('content')
<div class="login_wrapper">
    <div class="animate form login_form">
        <section class="login_content">
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('Kode verifikasi baru telah dikirim ke alamat email Anda.') }}
                </div>
            @endif

            <h1>{{ __('Verifikasi Email Kamu') }}</h1>
            <p class="text-center">{{ __('Silakan masukkan kode verifikasi yang dikirimkan ke email Anda.') }}</p>

            <form method="POST" action="{{ route('verification.verify') }}">
                @csrf
                <div>
                    <input id="verification_code" type="text" class="form-control @error('verification_code') is-invalid @enderror" name="verification_code" placeholder="{{ __('Verification Code') }}" required autofocus>
                    @error('verification_code')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="btn btn-default submit">
                        {{ __('Verify') }}
                    </button>
                </div>
            </form>

            <div class="clearfix"></div>
            <div class="separator">
                <p class="change_link">{{ __('Tidak menerima kode?') }}
                    <form method="POST" action="{{ route('verification.resend') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link p-0">{{ __('Kirim Ulang Kode Verifikasi') }}</button>
                    </form>
                </p>
                <div class="clearfix"></div>
                <br />
                <div>
                    <h1><i class="fa fa-paw"></i> Gentelella Alela!</h1>
                    <p>©2016 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
