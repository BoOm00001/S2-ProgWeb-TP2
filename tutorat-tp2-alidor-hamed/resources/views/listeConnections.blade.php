@extends('layouts.app')

@section('contenu')
  <h1>SIMULATION -- CONNECTION</h1>
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
        <td><a href="/simulerConnection/{{ $unEleve['matricule'] }}" class="btn btn-lg btn-success">Se connecter</a></td>
      </tr>
    @endforeach
    </tbody>
  </table>
@endsection