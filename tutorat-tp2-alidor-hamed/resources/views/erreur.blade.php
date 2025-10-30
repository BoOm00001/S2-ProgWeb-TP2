@extends('layouts.app')

@section('contenu')
<div class="row">
    <div class="col">
        <div class="alert alert-danger" role="alert">
            {{ $message }}
        </div>
        
        @if(isset($redirection))
            <a href="{{ $redirection }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Retour demandes disponibles
            </a>
        @else
            <a href="/" class="btn btn-primary">
                <i class="bi bi-house"></i> Retour simuler connection
            </a>
        @endif
    </div>
</div>
@endsection

