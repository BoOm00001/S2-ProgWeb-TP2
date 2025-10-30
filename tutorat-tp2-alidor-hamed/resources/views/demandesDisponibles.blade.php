@extends('layouts.app')

@section('contenu')
<h1>Demandes disponibles</h1>

@if(count($demandes) > 0)
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Session</th>
                <th># cours</th>
                <th class="col-sm-7">Aide demandée</th>
                <th class="col-sm-1">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($demandes as $demande)
                <tr>
                    <td>{{ $demande['session'] }}</td>
                    <td>{{ $demande['numeroCours'] }}</td>
                    <td>{{ $demande['descriptionDemande'] }}</td>
                    <td>
                        <a href="/assignerDemande/{{ $demande['numeroDemande'] }}" 
                           class="btn btn-primary">
                            M'assigner
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="alert alert-warning" role="alert">
        Aucune demande disponible
    </div>
@endif

<hr>
<a href="/demandesTuteur" class="btn btn-secondary">Retour à mes demandes</a>
@endsection
