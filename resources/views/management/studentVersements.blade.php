<!DOCTYPE html>
<html lang="fr">
@include('layout/head')
<body class="theme-black">
@include('layout/nav')

@if($utilisateur->role->libelle == 'ROOT' || $utilisateur->role->libelle == 'ADMIN' || $utilisateur->role->libelle == 'COMPTABLE')

<section class="content contact">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <h2>Versements de <b>{{$versements[0]->etudiant->nom}} {{$versements[0]->etudiant->prenoms}}</b></h2>
                    <ul class="breadcrumb ">
                        <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="zmdi zmdi-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{route('payments')}}">Paiements</a></li>
                        <li class="breadcrumb-item active">Versements</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12">
                @if(Session::has('success'))
                    <div class="alert alert-success m-2 text-center">
                        {{ Session::get('success') }}
                        @php
                            Session::forget('success');
                        @endphp
                    </div>
                @endif
                <div class="card">
                    <div class="body">   
                        <div class="table-responsive">
                            <table class="table table-bordered table-filter table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Montant à Payer</th>
                                        <th>Montant Payé</th>
                                        <th>Montant Restant</th>
                                        <th>Statut</th>
                                        <th>Validé par</th>
                                        <th>Commentaire</th>
                                    </tr>
                                </thead>                                
                                <tbody>
                                    @foreach($versements as $paiement)
                                    <tr>
                                        <td>{{ date('d/m/Y', strtotime($paiement->datePaiement)) }}</td>
                                        <td>{{ number_format($paiement->montantApayer, 0, ',', '.') }} F CFA</td>
                                        <td>{{ number_format($paiement->montantPaye, 0, ',', '.') }} F CFA</td>
                                        <td class="font-weight-bold">{{ number_format($paiement->montantRestant, 0, ',', '.') }} F CFA</td>
                                        <td>@if($paiement->montantRestant > 0)<span class="badge badge-danger">Acompté</span>@else<span class="badge badge-success">Soldé</span>@endif </td>
                                        <td>{{ $paiement->createdBy->nom }} {{ $paiement->createdBy->prenom }}</td>
                                        <td>{{ $paiement->commentaire }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</section>

<!-- Modal pour voir les détails de l'étudiant -->
<div class="modal fade" id="voirEtudiantModal" tabindex="-1" role="dialog" aria-labelledby="voirEtudiantModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="voirEtudiantModalLabel">Détails de l'étudiant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Nom :</strong> <span id="nomEtudiant"></span></p>
                <p><strong>Prénoms :</strong> <span id="prenomsEtudiant"></span></p>
                <p><strong>E-mail :</strong> <span id="emailEtudiant"></span></p>
                <p><strong>Date de naissance : </strong> <span id="datenaisEtudiant"></span></p>
                <p><strong>Niveau d'étude : </strong> <span id="niveauEtudiant"></span></p>
                <p><strong>Numéro de carte étudiant : </strong> <span id="numCarteEtudiant"></span></p>
                <p><strong>Numéro de téléphone : </strong> <span id="telephoneEtudiant"></span></p>
                <p><strong>Peut gérer la plateforme ? </strong> <span id="accessEtudiant"></span></p>
                <p><strong>Rôle : </strong> <span id="roleEtudiant"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@else
<section class="content">
    <h1 class="text-danger">
        VOUS N'AVEZ PAS LE DROIT D'ETRE ICI !!!
    </h1>
</section>
@endif

@include('layout/javascript')

<script>
    $(document).ready(function () {

        setTimeout(function () {
            $('.alert-success').fadeOut('slow');
        }, 2000);

        setTimeout(function () {
            $('.alert-danger').fadeOut('slow');
        }, 2000);


    });
</script>
</body>
</html>