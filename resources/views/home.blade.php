@extends('layouts.app')

@section('content')
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Bienvenid@ administrador@') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{ __('Ya has iniciado sesión!') }}
                </div>
            </div>
           
        </div>
    

@endsection

