    @extends('layouts.app')

    @section('contenu')

    
 
    <div class='container-fluid'>
        

        <div class='row'>
           <div class='col-12'>
                <p style="font-size: 30px;">
                  <strong> Rapport de séance de tutorat</strong>
                </p>
                <p style="font-size: 25px;">
                  <strong>  Numéro du cour: {{$demande['numeroCours']}} </strong>
                </p>
                <p style="font-size: 25px;">
                   <strong> Tutoré:  {{$tutore['prenom']}}  {{$tutore['nom']}} ( {{$tutore['matricule']}}) </strong>
                </p>
                <p style="font-size: 25px;">
                <strong>Problème: {{$demande['descriptionDemande']}}.</strong> 
                </p>
           </div>

            <form method="POST" action="/completerRapport" >

            @csrf

                <div class="form-group">
                  <label for="numeroDemande">Numero de la demande:</label>
                  <input type="number" class="form-control" id="numeroDemande" value ="{{$demande['numeroDemande']}}" readonly>
                </div>
                
              
                <div class="form-group">
                  <label for="commentaireTuteur">Commentaire du tuteur:</label>
                  <textarea class="form-control" id="commentaireTuteur" name="commentaireTuteur" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Remplir le raport</button>

             </form>

        </div>


    </div>

    @endsection