<!DOCTYPE html>
<html lang="fr">
@include('layout/head')
<body class="theme-black">
@include('layout/nav')

@if($utilisateur->role->libelle !== 'ETUDIANT')
<section class="content contact">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <h2>Gestion des Etudiants</h2>
                    <ul class="breadcrumb ">
                        <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="zmdi zmdi-home"></i></a></li>
                        <li class="breadcrumb-item active">Etudiants</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="body m-b-10">
                        <ul class="nav nav-tabs padding-0">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#Grid">Grille</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#List">Liste</a></li>
                            @if($utilisateur->role->libelle == 'ROOT' || $utilisateur->role->libelle == 'ADMIN' || $utilisateur->role->libelle == 'SUPPORT' || $utilisateur->role->libelle == 'AGENT DE SAISIE')
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Addnew">Nouveau</a></li>
                            @endif
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
                    <div class="tab-pane active" id="Grid">
                        <div class="row">
                            @foreach($etudiants as $etudiant)
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="card">
                                        <div class="body text-center">
                                            <div class="chart easy-pie-chart-1" data-percent="100">
                                                <span>
                                                    <img @if($etudiant->photo) src="{{asset('assets/images/etudiants/photos/'.$etudiant->photo)}}" @else src="{{asset('assets/images/avatar.png')}}" @endif alt="photo" class="rounded-circle"/>
                                                </span>
                                            </div>
                                            <h6 class="font-weight-bold" style="font-size: 1.2em;">@if($etudiant->username) {{$etudiant->username}} @else {{$etudiant->nom}} {{$etudiant->prenoms}} @endif</h6>
                                            <h6 class="text-primary">{{$etudiant->numCarteEtud}}</h6>
                                            <small>
                                                {{$etudiant->email}} <br>
                                                {{$etudiant->telephone}}
                                            </small>
                                            <hr>
                                            <ul class="social-links list-unstyled">
                                                <li><a title="voir" href="#" data-id="{{$etudiant->id}}" class="voir-etudiant"><i class="zmdi zmdi-eye"></i></a>
                                                <li>
                                                    <a title="modifier" href="{{route('getEtudiant', $etudiant->id)}}"><i class="zmdi zmdi-edit"></i></a>
                                                </li>
                                                @if($etudiant->id !== $utilisateur->id)
                                                    @if($utilisateur->role->libelle == 'ROOT' || $utilisateur->role->libelle == 'ADMIN' || $utilisateur->role->libelle == 'SUPPORT')
                                                        <li><a title="supprimer" href="#" data-id="{{$etudiant->id}}" data-nom="{{$etudiant->nom}}" data-prenoms="{{$etudiant->prenoms}}" class="supprimer-etudiant"><i class="zmdi zmdi-delete"></i></a></li>
                                                    @endif
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane" id="List">
                        <div class="card">
                            <div class="body">   
                                <div class="table-responsive">
                                    <table class="table table-bordered table-filter table-striped table-hover dataTable js-exportable">
                                        <thead>
                                            <tr>
                                                <th>Photo</th>
                                                <th>Nom et Prénom(s)</th>
                                                <th>Niveau</th>
                                                <th>N° carte</th>
                                                <th>N° téléphone</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>                                
                                        <tbody>
                                            @foreach($etudiants as $etudiant)
                                            <tr>
                                                <td><img @if($etudiant->photo) src="{{asset('assets/images/etudiants/photos/'.$etudiant->photo)}}" @else src="{{asset('assets/images/avatar.png')}}" @endif class="rounded-circle avatar" alt="photo"></td>
                                                <td>{{$etudiant->nom}} {{$etudiant->prenoms}} @if($etudiant->username) <span class="text-uppercase">({{$etudiant->username}})</span> @endif</td>
                                                <td>{{$etudiant->niveau}}</td>
                                                <td>{{$etudiant->numCarteEtud}}</td>
                                                <td>{{$etudiant->telephone}}</td>
                                                <td>
                                                    <button title="voir" class="btn btn-icon btn-neutral btn-icon-mini margin-0 voir-etudiant" data-id="{{$etudiant->id}}"><i class="zmdi zmdi-eye"></i></button>
                                                    <a href="{{route('getEtudiant', $etudiant->id)}}"><button title="modifier" class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-edit"></i></button></a>
                                                    @if($etudiant->id !== $utilisateur->id)
                                                        @if($utilisateur->role->libelle == 'ROOT' || $utilisateur->role->libelle == 'ADMIN' || $utilisateur->role->libelle == 'SUPPORT')
                                                            <button title="supprimer" class="btn btn-icon btn-neutral btn-icon-mini margin-0 supprimer-etudiant" data-id="{{$etudiant->id}}" data-nom="{{$etudiant->nom}}" data-prenoms="{{$etudiant->prenoms}}"><i class="zmdi zmdi-delete"></i></button>
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
                    <div class="tab-pane" id="Addnew">
                        <div class="card">
                            <div class="header">
                                <h2><strong>Ajouter</strong> un nouveau étudiant</h2>
                            </div>
                            <div class="body">
                                <form action="{{route('inscrire')}}" method="POST" enctype="multipart/form-data">
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
                                    <div class="alert alert-danger m-2 text-center" id="imageLourde">
                                        Photo trop lourde (> 3Mb)
                                    </div>
                                    <div class="alert alert-danger m-2 text-center" id="typeImage">
                                        Nous acceptons uniquement les images jpeg/jpg ou png
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-12 text-center mb-4 photo pt-2 img-upload">
                                            <label for="img_file_up">
                                                <img id="img_prv" src="{{asset('assets/images/avatar.png')}}" class="user_pic rounded img-raised" style="height:150px" alt="Avatar" />
                                            </label>
                                            <input name="photo" id="img_file_up" type="file" accept=".jpg,.jpeg,.png"/>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="nom">Nom</label>
                                                <input id="nom" name="nom" required="true" type="text" class="form-control" value="{{ old('nom') }}">
                                                @error('nom')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="prenoms">Prénom(s)</label>
                                                <input id="prenoms" name="prenoms" required="true" type="text" class="form-control" value="{{ old('prenoms') }}">
                                                @error('prenoms')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>                                    
                                        <div class="col-lg-4 col-md-12 mt-2">
                                            <label class="font-weight-bold" for="datenais">Date de naissance</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="zmdi zmdi-calendar"></i>
                                                </span>
                                                <input required="true" type="text" id="datenais" name="datenais" class="form-control datetimepicker" placeholder="Cliquer pour choisir la date..." value="{{ old('datenais') }}">
                                                @error('datenais')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 mt-2">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="niveau">Niveau d'étude</label>
                                                <input id="niveau" name="niveau" required="true" type="text" class="form-control" value="{{ old('niveau') }}">
                                                @error('niveau')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 mt-2">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="numCarteEtud">Numéro de carte étudiant</label>
                                                <input id="numCarteEtud" name="numCarteEtud" required="true" type="text" class="form-control" value="{{ old('numCarteEtud') }}">
                                                @error('numCarteEtud')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 mt-2">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="email">E-mail</label>
                                                <input id="email" name="email" required="true" type="email" class="form-control" value="{{ old('email') }}">
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 mt-2">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="telephone">Numéro de téléphone</label>
                                                <input id="telephone" name="telephone" required="true" type="tel" class="form-control" value="{{ old('telephone') }}">
                                                @error('telephone')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        @if($utilisateur->role->libelle == 'ROOT' || $utilisateur->role->libelle == 'ADMIN' || $utilisateur->role->libelle == 'SUPPORT')
                                        <div class="col-lg-4 col-md-12 mt-4">
                                            <div class="checkbox">
                                                <input id="access" name="access" type="checkbox">
                                                <label class="font-weight-bold" for="access">Peut gérer la plateforme ?</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 mt-2 accessOk">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="username">Nom d'utilisateur</label>
                                                <input id="username" name="username" type="text" class="form-control" value="{{ old('username') }}">
                                                @error('username')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 mt-4 accessOk">
                                            <div class="form-group">
                                                <select class="form-control show-tick" id="roleID" name="roleID">
                                                    <option selected value="" disabled>-- Choisir le rôle --</option>
                                                    @foreach($roles as $role)
                                                        <option value="{{$role->id}} {{ (old('roleID') == $role->id) ? 'selected' : '' }}">{{$role->libelle}}</option>
                                                    @endforeach
                                                </select>
                                                @error('roleID')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        @endif
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
@else
<section class="content">
    <h1 class="text-danger">
        VOUS N'AVEZ PAS LE DROIT D'ETRE ICI !!!
    </h1>
</section>
@endif

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
                @if($utilisateur->role->libelle == 'ROOT' || $utilisateur->role->libelle == 'ADMIN' || $utilisateur->role->libelle == 'SUPPORT')
                <p><strong>Peut gérer la plateforme ? </strong> <span id="accessEtudiant"></span></p>
                <p><strong>Rôle : </strong> <span id="roleEtudiant"></span></p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@if($utilisateur->role->libelle == 'ROOT' || $utilisateur->role->libelle == 'ADMIN' || $utilisateur->role->libelle == 'SUPPORT')
<!-- Modal pour supprimer les détails de l'étudiant -->
<div class="modal fade" id="supprimerEtudiantModal" tabindex="-1" role="dialog" aria-labelledby="supprimerEtudiantModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="supprimerEtudiantModalLabel">Supprimer l'étudiant</h5>
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
@endif

@include('layout/javascript')

<script>
    $(document).ready(function () {
        $('#imageLourde').hide();
        $('#typeImage').hide();
        $('.accessOk').hide();
        $('#access').change(function () {
            if ($(this).is(':checked')) {
                $('.accessOk').show();
            } else {
                $('.accessOk').hide();
            }
        });

        @if($errors->any())
            $(document).ready(function () {
                $('#Grid').removeClass('active');
                $('#Addnew').addClass('active');

                $('a[href="#Grid"]').removeClass('active');
                $('a[href="#Addnew"]').addClass('active');
            });
        @endif

        @if($errors->has('username'))
            $('#access').prop('checked', true);
            $('.accessOk').show();
        @endif

        setTimeout(function () {
            $('.alert-success').fadeOut('slow');
        }, 2000);

        setTimeout(function () {
            $('.alert-danger').fadeOut('slow');
        }, 2000);

        $('#img_file_up').on('change', function(ev){
                //Verifier image
                var filedata = this.files[0]
                var imgtype = filedata.type
                var match = ['image/jpeg', 'image/jpg', 'image/png']
                if((imgtype!==match[0]) && (imgtype!==match[1]) && (imgtype!==match[2])){
                    $('#typeImage').show();
                    setTimeout(function () {
                        $('#typeImage').fadeOut('slow');
                    }, 5000);
                }
                else{
                    if(this.files[0].size > 3145728){
                        $('#imageLourde').show();
                        setTimeout(function () {
                            $('#imageLourde').fadeOut('slow');
                        }, 5000);
                    }
                    else{
                        //Voir l'image
                        var reader = new FileReader()
                        reader.onload = function(ev){
                            $('#img_prv').attr('src', ev.target.result)
                        }
                        reader.readAsDataURL(this.files[0])
                    }
                    
                }
            })

            $('#btnuploadphoto').click(function() {
                $("#photo_file_up").click();
            })

            $('#photo_file_up').change(function() {
                if(this.files[0].size > 3145728){
                    $('#imageLourde').show();
                        setTimeout(function () {
                            $('#imageLourde').fadeOut('slow');
                        }, 5000);
                }
            });

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