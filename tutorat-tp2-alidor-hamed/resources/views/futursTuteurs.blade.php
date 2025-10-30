@extends('layouts.app')

@section('contenu')
  <h1>Décerner l'accréditation de tuteur</h1>
  <p>Veuillez sélectionner l'étudiant(e) qui a complété avec succès la formation obligatoire. </p>
  <p>Le tuteur (ou tutrice) aura la possibilité de répondre aux demandes de support.</p>
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>Matricule</th>
        <th>Prénom</th>
        <th>Nom</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    @foreach($eleves as $unEleve)
      <tr>
        <td>{{ $unEleve['matricule'] }}</td>
        <td>{{ $unEleve['prenom'] }}</td>
        <td>{{ $unEleve['nom'] }}</td>
        <td><a href="/accrediter/{{ $unEleve['matricule'] }}" class="btn btn-large btn-success">Accréditer</a></td>
      </tr>
    @endforeach  
    </tbody>
  </table>
@endsection