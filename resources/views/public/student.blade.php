<!DOCTYPE html>
<html lang="fr">
@include('layout/head')
<body class="theme-black">

<div class="authentication">
    <div class="container">
        <div class="col-12 content-center">
            <div class="row">
                <div class="mx-auto">
                    <div class="card-plain @if($montantRestant > 0) bg-danger @else bg-info @endif" style="width:400px; color:white;">
                        <div class="body widget-user">
                            <div class="row">
                                <div class="text-center mx-auto">
                                    <img class="rounded-circle" style="height: 250px" @if($etudiant->photo) src="{{asset('assets/images/etudiants/photos/'.$etudiant->photo)}}" @else src="{{asset('assets/images/avatar.png')}}" @endif alt="photo">
                                </div>
                            </div>
                            <hr class="bg-white">
                            <div class="row">
                                <div class="mx-auto">
                                    <h5><b>{{$etudiant->nom}}  {{$etudiant->prenoms}}</b></h5>
                                    <p class="text-white m-b-0">{{$etudiant->numCarteEtud}} <br>
                                        <small>{{$etudiant->email}} <br> {{$etudiant->telephone}}</small>
                                    </p> 
                                </div>
                            </div>
                            <hr class="bg-white">
                            <div class="row">
                                @if($montantRestant > 0)
                                <div class="col-12">
                                    <h5 class="m-b-0">{{ number_format($montantRestant, 0, ',', '.') }} F CFA</h5>
                                    <small>Restant à payer</small>
                                </div>
                                @else
                                <div class="col-12">
                                    <h5 class="m-b-0 text-uppercase">Vous avez Soldé</h5>
                                    <small>Merci pour votre inscription !</small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layout/javascript')

</body>
</html>