@extends('layouts.app')

@section('contenu')
<div class="alert alert-danger" role="alert">
Vous devez être assigné à cette demande pour compléter le rapport
</div>
<a href="/demandesTuteur" class="btn btn-primary">Demander tuteur</a>
@endsection