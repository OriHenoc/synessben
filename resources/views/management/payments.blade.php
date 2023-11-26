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
                    <h2>Gestion des Paiements</h2>
                    <ul class="breadcrumb ">
                        <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="zmdi zmdi-home"></i></a></li>
                        <li class="breadcrumb-item active">Paiements</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="body m-b-10">
                        <ul class="nav nav-tabs padding-0">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#List">Liste</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Addnew">Nouveau</a></li>
                        </ul>
                    </div>
                </div>
                @if(Session::has('success'))
                    <div class="alert alert-success m-2 text-center">
                        {{ Session::get('success') }}
                        @php
                            Session::forget('success');
                        @endphp
                    </div>
                @endif
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="List">
                        <div class="card">
                            <div class="body">   
                                <button type="button" class="btn btn-round btn-simple btn-sm btn-default btn-filter" data-target="all">Tous</button>
                                <button type="button" class="btn btn-round btn-simple btn-sm btn-success btn-filter" data-target="solde">Soldés</button>
                                <button type="button" class="btn btn-round btn-simple btn-sm btn-danger btn-filter" data-target="partie">Payés partiellement</button>                            
                                <div class="table-responsive">
                                    <table class="table table-bordered table-filter table-striped table-hover dataTable js-exportable">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Etudiant</th>
                                                <th>Montant à Payer</th>
                                                <th>Montant Payé</th>
                                                <th>Montant Restant</th>
                                                <th>Statut</th>
                                                <th>Fichiers</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>                                
                                        <tbody>
                                            @foreach($paiementsEtudiants as $paiement)
                                            <tr @if($paiement->montantRestant > 0) data-status="partie" @else data-status="solde" @endif">
                                                <td>{{ date('d/m/Y', strtotime($paiement->datePaiement)) }}</td>
                                                <td>{{$paiement->etudiant->nom}} {{$paiement->etudiant->prenoms}}</td>
                                                <td>{{ number_format($paiement->montantApayer, 0, ',', '.') }} F CFA</td>
                                                <td>{{ number_format($paiement->montantPaye, 0, ',', '.') }} F CFA</td>
                                                <td class="font-weight-bold">{{ number_format($paiement->montantRestant, 0, ',', '.') }} F CFA</td>
                                                <td>@if($paiement->montantRestant > 0)<span class="badge badge-danger">Acompté</span>@else<span class="badge badge-success">Soldé</span>@endif </td>
                                                <td>
                                                    <button title="voir QR Code" class="btn btn-icon btn-info btn-icon-mini margin-0 voir-qrcode" data-etudiant="{{$paiement->etudiant->id}}"><i class="material-icons">center_focus_strong</i></button>
                                                    <button title="voir Reçu" class="btn btn-icon btn-info btn-icon-mini margin-0 voir-recu" data-etudiant="{{$paiement->etudiant->id}}"><i class="material-icons">picture_as_pdf</i></button>
                                                </td>
                                                <td>
                                                    <a href="{{route('getAllEtudiantPayments', $paiement->etudiant->id)}}"><button title="voir tout" class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-eye"></i></button></a>
                                                    @if($paiement->montantRestant > 0)<button title="ajouter un versement" class="btn btn-icon btn-neutral btn-icon-mini margin-0 ajouter-versement" data-id="{{$paiement->id}}"><i class="zmdi zmdi-plus-circle"></i></button>@endif
                                                    @if($utilisateur->role->libelle == 'ROOT' || $utilisateur->role->libelle == 'ADMIN')
                                                    <button title="supprimer" class="btn btn-icon btn-neutral btn-icon-mini margin-0 supprimer-paiement" data-etudiant="{{$paiement->etudiant->id}}" data-nom="{{$paiement->etudiant->nom}}" data-prenoms="{{$paiement->etudiant->prenoms}}"><i class="zmdi zmdi-delete"></i></button>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="Addnew">
                        <div class="card">
                            <div class="header">
                                <h2><strong>Ajouter</strong> un nouveau paiement</h2>
                                <small>NB : Ceci ne concerne que les étudiants n'ayant jamais fait de versement.</small>
                            </div>
                            <div class="body">
                                <form action="{{route('payer')}}" method="POST">
                                    {{csrf_field()}}
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <strong>Erreur d'enregistrement :</strong>
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    @if(Session::has('error'))
                                        <div class="alert alert-danger mt-4 text-center">
                                            {{ Session::get('error') }}
                                            @php
                                                Session::forget('error');
                                            @endphp
                                        </div>
                                    @endif
                                    <div class="row clearfix">
                                        <div class="col-lg-6 col-md-12">
                                            <b>Etudiant</b>
                                            <div class="form-group">
                                                <select class="form-control show-tick" id="etudiantID" name="etudiantID" required="required">
                                                    <option selected value="" disabled>-- Séléctionner l'étudiant --</option>
                                                    @foreach($etudiantsWithoutPaiements as $etudiant)
                                                        <option value="{{$etudiant->id}} {{ (old('etudiantID') == $etudiant->id) ? 'selected' : '' }}">{{$etudiant->nom}} {{$etudiant->prenoms}}</option>
                                                    @endforeach
                                                </select>
                                                @error('etudiantID')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mt-1 col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="montantApayer">Montant à payer</label>
                                                <input id="montantApayer" @if($utilisateur->role->libelle == 'COMPTABLE') readonly @endif name="montantApayer" required="true" type="number" step="500" min="0" class="form-control" value="30000">
                                                @error('montantApayer')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="montantPaye">Montant payé</label>
                                                <input id="montantPaye" name="montantPaye" required="true" type="number" step="500" min="0" class="form-control" value="{{ old('montantPaye') }}">
                                                @error('montantPaye')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>           
                                        <div class="col-lg-4 col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="montantRestant">Montant restant</label>
                                                <input id="montantRestant" readonly required="true" type="number" step="500" min="0" class="form-control" value="{{ old('montantRestant') }}">
                                                @error('montantRestant')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>                           
                                        <div class="col-lg-4 col-md-12">
                                            <label class="font-weight-bold" for="datePaiement">Date de paiement</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="zmdi zmdi-calendar"></i>
                                                </span>
                                                <input required="true" type="text" id="datePaiement" name="datePaiement" class="form-control datetimepicker" placeholder="Cliquer pour choisir la date..." value="{{ old('datePaiement') }}">
                                                @error('datePaiement')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <div class="form-group">
                                                <textarea name="commentaire" cols="30" rows="5" placeholder="Un commentaire ?" class="form-control" aria-required="true">{{ old('commentaire') }}</textarea>
                                                @error('commentaire')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button class="btn btn-primary btn-round" type="submit">Enregistrer</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</section>

<!-- Modal pour voir les détails de l'étudiant -->
<div class="modal fade" id="voirRecuModal" tabindex="-1" role="dialog" aria-labelledby="voirRecuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="voirRecuModalLabel">Reçu de paiement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card" id="details">
                    <div class="body imprimable">                                
                        <div class="row text-center">
                            <div class="col-md-2 col-sm-3 m-0 p-0" style="max-width: 100px;">
                                <img src="{{asset('assets/images/logo.svg')}}" alt="SYNESS" style="height: 100px;">
                            </div>
                            <div class="col-md-7 col-sm-6 m-0 p-0">
                                <h5 class="text-uppercase">
                                    <b>SYNDICAT NATIONAL DES ETUDIANTS EN SCIENCE DE LA SANTé</b>
                                </h5>
                            </div>
                            <div class="col-md-3 col-sm-3 text-right m-0 p-0 right" style="float:right; right:0;">
                                <p class="m-b-0">Etudiant : <strong><span id="factureNom"></span> <span id="facturePrenoms"></span></strong></p>
                                <p class="m-b-0">N° Carte : <strong><span id="factureCarte"></span></strong></p>
                            </div>
                        </div>
                        <div class="mt-40"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="newtab"></table>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>NL DOS LARGE</h6>
                                <img class="img-fluid" id="qrCodeImageRecu" src="" alt="QRCODE" style="height: 100px">
                            </div>
                            <div class="col-md-6 text-right">                                   
                                <h4 class="m-b-0 m-t-10">
                                    <span class="badge text-uppercase badge-danger m-b-0 p-4" id="factureStatut"></span>
                                </h4>
                            </div>
                            <div class="row mt-2 col-12 p-0">
                                <div class="col-6" style="float: left"><h6 class="text-uppercase">L'élite syndicale</h6></div>
                                <div class="col-6 p-0" style="text-align:right"><h6 class="text-uppercase">SYNESS CONFIANCE !!!</h6></div>
                            </div>
                        </div>                                
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hidden-print col-md-12 text-right">
                    <form id="envoiRecu" method="post" class="form" action="{{route('envoyerRecu')}}">
                        {{ csrf_field() }}
                        <input type="hidden" name="image_data" id="image_data">
                        <input type="hidden" name="etudiantID" id="recuEtudiantID">
                        <input type="hidden" name="send_receipt" id="send_receipt">
                        <button id="printRecu" type="button" class="btn btn-info btn-icon  btn-icon-mini btn-round"><i class="zmdi zmdi-print"></i></button>
                        <button id="sendRecu" type="submit" class="btn btn-primary btn-round">Envoyer au concerné</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="qrCodeModal" tabindex="-1" role="dialog" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrCodeModalLabel">QR CODE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="body imprimableOk text-center">
                    <div class="row text-center">
                        <div class="mx-auto">
                            <img class="img-fluid" id="qrCodeImage" src="" alt="QRCODE">
                        </div>
                    </div>                               
                </div>
            </div>            
            <div class="modal-footer">
                <div class="hidden-print col-md-12 text-right">
                    <form id="envoiQrCode" method="post" class="form" action="{{route('envoyerQrCode')}}">
                        {{ csrf_field() }}
                        <input type="hidden" name="image_data" id="image_data">
                        <input type="hidden" name="etudiantID" id="qrEtudiantID">
                        <input type="hidden" name="send_receipt" id="send_receipt">
                        <a id="qrLink" target="_blank" href=""><button type="button" class="btn btn-info btn-icon  btn-icon-mini btn-round"><i class="zmdi zmdi-save"></i></button></a>
                        <button id="sendQrCode" type="submit" class="btn btn-primary btn-round">Envoyer au concerné</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if($utilisateur->role->libelle == 'ROOT' || $utilisateur->role->libelle == 'ADMIN')
<!-- Modal pour supprimer les détails de l'étudiant -->
<div class="modal fade" id="supprimerPaiementModal" tabindex="-1" role="dialog" aria-labelledby="supprimerPaiementModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="supprimerPaiementModalLabel">Supprimer les paiements</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Êtes-vous certain de vouloir supprimer <strong><span class="text-danger">TOUS LES PAIEMENTS</span> de <span id="supNomEtudiant"></span> <span id="supPrenomsEtudiant"></span> </strong> ?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <form id="supPaiements" method="post" class="form" action="{{route('supprimerPaiements', '')}}">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-danger btn-sup-paiements">Oui, supprimer</button>
                </form>  
            </div>
        </div>
    </div>
</div>
@endif

<div class="modal fade" id="ajoutVersementModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="defaultModalLabel">Ajouter un versement</h4>
            </div>
            <form action="{{route('payer')}}" method="POST">
                @csrf
                <input type="hidden" id="ajoutPaymEtudiantId" name="etudiantID">
                <input type="hidden" id="ajoutPaymMtBuy" name="montantApayer">
                <div class="modal-body">
                    <div class="form-group">
                        <p><strong>Montant restant à payer actuellement :</strong> <span class="text-danger" id="ajoutPaymMontantApayer"></span></p>
                    </div>
                    <div class="form-group mt-2">
                        <div class="form-line">
                            <div class="row">
                                <div class="col-4">
                                    <strong>Montant payé : </strong>
                                </div>
                                <div class="col-8">
                                    <input id="ajoutPaymMontantPaye" name="montantPaye" required="true" type="number" step="500" min="0" class="form-control" placeholder="Montant payé">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <p><strong>Montant qui restera à régler après versement :</strong> <span class="text-info" id="mtnRest">........</span></p>
                    </div>
                    <div class="form-group mt-2">
                        <div class="row">
                            <div class="col-4 mt-2">
                                <strong>Date paiement : </strong>
                            </div>
                            <div class="col-8">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="zmdi zmdi-calendar"></i>
                                    </span>
                                    <input required="true" type="text" id="ajoutPaymDatePaiement" name="datePaiement" class="form-control datetimepicker" placeholder="Cliquer pour choisir la date...">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <textarea name="commentaire" cols="30" rows="5" placeholder="Un commentaire ?" class="form-control" aria-required="true"></textarea>
                    </div>    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-simple btn-round waves-effect" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary btn-round waves-effect">Ajouter le versement</button>
                </div>
            </form>
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

        @if($errors->any())
            $(document).ready(function () {
                $('#List').removeClass('active');
                $('#Addnew').addClass('active');

                $('a[href="#List"]').removeClass('active');
                $('a[href="#Addnew"]').addClass('active');
            });
        @endif

        setTimeout(function () {
            $('.alert-success').fadeOut('slow');
        }, 2000);

        setTimeout(function () {
            $('.alert-danger').fadeOut('slow');
        }, 2000);

        $('.ajouter-versement').click(function (e) {
            e.preventDefault();
            var id = $(this).data('id');

            $.ajax({
                type: 'GET',
                headers:{'X-CSRF-Token':$('meta[name=csrf_token]').attr('content')},
                async:true,
                contentType:false,
                url: '/ajouter-versement/' + id,
                success: function (data) {
                    $('#ajoutPaymEtudiantId').val(data.etudiantID);
                    $('#ajoutPaymMtBuy').val(data.montantApayer);
                    $('#ajoutPaymMontantApayer').text(data.montantApayer);
                    $('#ajoutVersementModal').modal('show');
                },
                error: function (error) {
                    console.log('Erreur lors de la récupération des détails de paiement', error);
                }
            });
        });

        $('.voir-recu').click(function (e) {
            e.preventDefault();
            var etudiantID = $(this).data('etudiant');

            $.ajax({
                type: 'GET',
                headers:{'X-CSRF-Token':$('meta[name=csrf_token]').attr('content')},
                async:true,
                contentType:false,
                url: '/recu-versements-etudiant/' + etudiantID,
                success: function (data) {
                    $('#recuEtudiantID').val(etudiantID);
                    $('#factureNom').text(data.nom);
                    $('#facturePrenoms').text(data.prenoms);
                    $('#factureCarte').text(data.carte);
                    var link = 'https://synessben.committeam.com/assets/images/etudiants/qrcode/'+ data.image;
                    $('#qrCodeImageRecu').attr('src', link);

                    var factureStatut = $('#factureStatut');
                    factureStatut.text(data.statut);

                    factureStatut.removeClass('badge-danger badge-success');

                    if (data.statut === 'Acompté') {
                        factureStatut.addClass('badge-danger');
                    } else if (data.statut === 'Soldé') {
                        factureStatut.addClass('badge-success');
                    }

                    var versements = data.versements;
                    var table = $('#newtab');
                    
                    table.empty();

                    table.append(`
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Montant à Payer</th>
                        <th>Montant Payé</th>
                        <th>Montant Restant</th>
                    </tr>
                </thead>
                <tbody>
            `);

            // Add table rows
            versements.forEach(function (paiement) {
                var dateObject = new Date(paiement.datePaiement);
                var formattedDate = dateObject.toLocaleDateString('en-GB');
                var formattedMontantApayer = new Intl.NumberFormat('fr-FR').format(paiement.montantApayer);
                var formattedMontantPaye = new Intl.NumberFormat('fr-FR').format(paiement.montantPaye);
                var formattedmontantRestant = new Intl.NumberFormat('fr-FR').format(paiement.montantRestant);
                var row = `
                    <tr>
                        <td>${formattedDate}</td>
                        <td>${formattedMontantApayer} F CFA</td>
                        <td>${formattedMontantPaye} F CFA</td>
                        <td class="font-weight-bold">${formattedmontantRestant} F CFA</td>
                    </tr>
                `;
                table.append(row);
            });

            table.append(`
                </tbody>
            `);

                    $('#voirRecuModal').modal('show');
                },
                error: function (error) {
                    console.log('Erreur lors de la récupération des détails de paiements', error);
                }
            });
        });

        var montantPayeInput = $('#ajoutPaymMontantPaye');

        // Assuming you have a span with id 'mtnRest'
        var mtnRestSpan = $('#mtnRest');

        // Assuming you have an input field with id 'ajoutPaymMontantApayer'
        var montantApayerInput = $('#ajoutPaymMontantApayer');

                montantPayeInput.on('input', function() {
            // Get the values of #ajoutPaymMontantApayer and #ajoutPaymMontantPaye
            var montantApayer = parseInt(montantApayerInput.text()) || 0;
            var montantPaye = parseInt($(this).val()) || 0;

            // Calculate the difference and ensure it's not negative
            var mtnRest = Math.max(0, montantApayer - montantPaye);

            // Update the content of #mtnRest
            mtnRestSpan.text(mtnRest);
        });

        $('.supprimer-paiement').click(function (e) {
            e.preventDefault();
            var idEtudiant = $(this).data('etudiant');
            var nomEtudiant = $(this).data('nom');
            var prenomsEtudiant = $(this).data('prenoms');
            $('#supPaiements').attr('action', "{{route('supprimerPaiements', '')}}" + '/' + idEtudiant);
            $('#supNomEtudiant').text(nomEtudiant);
            $('#supPrenomsEtudiant').text(prenomsEtudiant);
            $('#supprimerPaiementModal').modal('show');
        });

        function updateMontantRestant() {
            var montantApayer = parseInt($('#montantApayer').val()) || 0;
            var montantPaye = parseInt($('#montantPaye').val()) || 0;
            var montantRestant = montantApayer - montantPaye;
            
            $('#montantRestant').val(montantRestant.toFixed());
        }
        $('#montantApayer, #montantPaye').on('input', function () {
            updateMontantRestant();
        });
        
        updateMontantRestant();

        $('#printRecu').on('click', function () {
        // Use html2canvas to capture the content as an image
        html2canvas(document.querySelector('.imprimable')).then(canvas => {
            // Open a new window with the image
            var newWindow = window.open('', '_blank');
            newWindow.document.write('<html><head><title>SYNESS-BEN : Reçu de paiement</title></head><body>');
                newWindow.document.write('<style>body {font-family: Arial, sans-serif;}</style>');
       

            newWindow.document.write('<img src="' + canvas.toDataURL() + '" />');
            newWindow.document.write('</body></html>');
            newWindow.document.close();
            // Print the new window
            newWindow.onload = function () {
                
            // Print the new window
            newWindow.print();
        };
        });
        });

        $('#printQrCode').on('click', function () {
        // Use html2canvas to capture the content as an image
        html2canvas(document.querySelector('.imprimableOk')).then(canvas => {
            // Open a new window with the image
            var newWindow = window.open('', '_blank');
            newWindow.document.write('<html><head><title>SYNESS-BEN : QR CODE</title></head><body>');
                newWindow.document.write('<style>body {font-family: Arial, sans-serif;}</style>');
       

            newWindow.document.write('<img src="' + canvas.toDataURL() + '" />');
            newWindow.document.write('</body></html>');
            newWindow.document.close();
            // Print the new window
            newWindow.onload = function () {
                
            // Print the new window
            newWindow.print();
        };
        });
        });

    $('#sendRecu').on('click', function (e) {
        e.preventDefault();
    // Use html2canvas to capture the content as an image
        html2canvas(document.querySelector('.imprimable')).then(canvas => {
            // Convert the canvas to a data URL
            var imgData = canvas.toDataURL('image/png');

            // Set the image data in the hidden field
            $('#image_data').val(imgData);

            // Set the flag to indicate that the receipt should be sent
            $('#send_receipt').val('1');

            // Submit the form
            $('#envoiRecu').submit();
        });
    });

    $('.voir-qrcode').click(function (e) {
    e.preventDefault();
    var etudiantID = $(this).data('etudiant');

    $.ajax({
        type: 'GET',
        headers: { 'X-CSRF-Token': $('meta[name=csrf_token]').attr('content') },
        async: true,
        contentType: false,
        url: '/qrcode-etudiant/' + etudiantID,
        success: function (data) {
            $('#qrEtudiantID').val(etudiantID);
            var link = 'https://synessben.committeam.com/assets/images/etudiants/qrcode/'+ data.image;
            $('#qrCodeImage').attr('src', link);
            $('#qrLink').attr('href', link);
            $('#qrCodeModal').modal('show');
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
});


    });
</script>
</body>
</html>