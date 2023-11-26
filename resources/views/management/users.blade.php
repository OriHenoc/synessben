<!DOCTYPE html>
<html lang="fr">
@include('layout/head')
<body class="theme-black">
@include('layout/nav')

@if($utilisateur->role->libelle == 'ROOT' || $utilisateur->role->libelle == 'ADMIN' || $utilisateur->role->libelle == 'SUPPORT' || $utilisateur->role->libelle == 'SUPERVISEUR')

<section class="content contact">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <h2>Gestion des Utilisateurs</h2>
                    <ul class="breadcrumb ">
                        <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="zmdi zmdi-home"></i></a></li>
                        <li class="breadcrumb-item active">Utilisateurs</li>
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
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="card">
                        <div class="body">   
                            <div class="table-responsive">
                                <table class="table table-bordered table-filter table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th>Photo</th>
                                            <th>Nom et Prénom(s)</th>
                                            <th>Rôle</th>
                                            <th>Email</th>
                                            <th>N° téléphone</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>                                
                                    <tbody>
                                        @foreach($utilisateurs as $etudiant)
                                        <tr>
                                            <td><img @if($etudiant->photo) src="{{asset('assets/images/etudiants/photos/'.$etudiant->photo)}}" @else src="{{asset('assets/images/avatar.png')}}" @endif class="rounded-circle avatar" alt="photo"></td>
                                            <td>{{$etudiant->nom}} {{$etudiant->prenoms}} @if($etudiant->username) <span class="text-uppercase">({{$etudiant->username}})</span> @endif</td>
                                            <td><b>{{$etudiant->role->libelle}}</b></td>
                                            <td>{{$etudiant->email}}</td>
                                            <td>{{$etudiant->telephone}}</td>
                                            <td>
                                                <button title="voir" class="btn btn-icon btn-neutral btn-icon-mini margin-0 voir-etudiant" data-id="{{$etudiant->id}}"><i class="zmdi zmdi-eye"></i></button>
                                                @if($utilisateur->role->libelle !== 'ROOT')
                                                    @if($etudiant->role->libelle !== 'ROOT')
                                                        <a href="{{route('getEtudiant', $etudiant->id)}}"><button title="modifier" class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-edit"></i></button></a>
                                                        @if($etudiant->id !== $utilisateur->id)
                                                            @if($etudiant->role->libelle !== 'ADMIN')
                                                                <button title="supprimer" class="btn btn-icon btn-neutral btn-icon-mini margin-0 supprimer-etudiant" data-id="{{$etudiant->id}}" data-nom="{{$etudiant->nom}}" data-prenoms="{{$etudiant->prenoms}}"><i class="zmdi zmdi-delete"></i></button>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @else
                                                    @if($etudiant->role->libelle !== 'ROOT')
                                                        <a href="{{route('getEtudiant', $etudiant->id)}}"><button title="modifier" class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-edit"></i></button></a>
                                                        @if($etudiant->id !== $utilisateur->id)
                                                            <button title="supprimer" class="btn btn-icon btn-neutral btn-icon-mini margin-0 supprimer-etudiant" data-id="{{$etudiant->id}}" data-nom="{{$etudiant->nom}}" data-prenoms="{{$etudiant->prenoms}}"><i class="zmdi zmdi-delete"></i></button>
                                                        @endif
                                                    @endif
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
            </div>
        </div>        
    </div>
</section>

<!-- Modal pour voir les détails de l'étudiant -->
<div class="modal fade" id="voirEtudiantModal" tabindex="-1" role="dialog" aria-labelledby="voirEtudiantModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="voirEtudiantModalLabel">Détails de l'utilisateur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Nom :</strong> <span id="nomEtudiant"></span></p>
                <p><strong>Prénoms :</strong> <span id="prenomsEtudiant"></span></p>
                <p><strong>Rôle : </strong> <span id="roleEtudiant"></span></p>
                <p><strong>E-mail :</strong> <span id="emailEtudiant"></span></p>
                <p><strong>Numéro de téléphone : </strong> <span id="telephoneEtudiant"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour supprimer les détails de l'étudiant -->
<div class="modal fade" id="supprimerEtudiantModal" tabindex="-1" role="dialog" aria-labelledby="supprimerEtudiantModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="supprimerEtudiantModalLabel">Supprimer l'utilisateur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Êtes-vous certain de vouloir supprimer <strong><span id="supNomEtudiant"></span> <span id="supPrenomsEtudiant"></span> </strong> ?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <form id="supEtudiant" method="post" class="form" action="{{route('supprimerEtudiant', '')}}">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-danger btn-sup-etudiant">Oui, supprimer</button>
                </form>  
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

        $('.voir-etudiant').click(function (e) {
            e.preventDefault();
            var etudiantId = $(this).data('id');

            $.ajax({
                type: 'GET',
                headers:{'X-CSRF-Token':$('meta[name=csrf_token]').attr('content')},
                async:true,
                contentType:false,
                url: '/etudiant/' + etudiantId,
                success: function (data) {
                    $('#photoEtudiant').text(data.photo);
                    $('#nomEtudiant').text(data.nom);
                    $('#prenomsEtudiant').text(data.prenoms);
                    $('#datenaisEtudiant').text(data.datenais);
                    $('#niveauEtudiant').text(data.niveau);
                    $('#numCarteEtudiant').text(data.numCarteEtud);
                    $('#emailEtudiant').text(data.email);
                    $('#telephoneEtudiant').text(data.telephone);
                    $('#accessEtudiant').text(data.access);
                    $('#usernameEtudiant').text(data.username);
                    $('#roleEtudiant').text(data.role);
                    $('#voirEtudiantModal').modal('show');
                },
                error: function (error) {
                    console.log('Erreur lors de la récupération des détails de l\'étudiant', error);
                }
            });
        });

        $('.supprimer-etudiant').click(function (e) {
            e.preventDefault();
            var idEtudiant = $(this).data('id');
            var nomEtudiant = $(this).data('nom');
            var prenomsEtudiant = $(this).data('prenoms');
            $('#supEtudiant').attr('action', "{{route('supprimerEtudiant', '')}}" + '/' + idEtudiant);
            $('#supNomEtudiant').text(nomEtudiant);
            $('#supPrenomsEtudiant').text(prenomsEtudiant);
            $('#supprimerEtudiantModal').modal('show');
        });
    });
</script>
</body>
</html>