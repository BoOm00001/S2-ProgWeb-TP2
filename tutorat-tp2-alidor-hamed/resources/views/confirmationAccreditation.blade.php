@extends('layouts.app')

@section('contenu')
  <h1>Confirmation d'accréditation</h1>
  <p>L'étudiant(e) répondant au matricule <strong>{{ $eleve['matricule'] }}</strong> a dorénavant la possibilité d'agir comme <u>tuteur</u
  >.</p>
  <p>Toutes nos félicitations à <strong>{{ $eleve['prenom'] }} {{ $eleve['nom'] }}</strong> pour son engagement.</p>
  <a href="/accrediter" class="btn btn-large btn-primary">Revernir aux accréditations</a>
@endsection