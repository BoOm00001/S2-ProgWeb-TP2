@extends('layouts.app')

@section('contenu')
  <h3>{{ $eleve['prenom'] }} {{ $eleve['nom'] }} ({{ $eleve['matricule'] }})</h3>
  <h1>Je souhaite avoir de l'aide...</h1>
  <form method="post" action="/inscrireDemande">
    @csrf
    <div class="mb-3">
      <label class="form-label" for="numeroCours">Pour le cours:</label><br>
      <select class="form-select" name="numeroCours">
      @foreach($cours as $unCours)
        <option value="{{ $unCours['numeroCours'] }}">{{ $unCours['numeroCours'] }} {{ $unCours['titre'] }} ({{ $unCours['session'] }})</option>
      @endforeach
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label" for="description">Objectif(s) du support:</label><br>
      <textarea class="form-control" name="description" rows="5" cols="60" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Soumettre la demande</button>
  </form>
@endsection