<!DOCTYPE html>
<html lang="fr">
@include('layout/head')
<body class="theme-black">
@include('layout/nav')

<section class="content contact">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <h2>Gestion des Etudiants</h2>
                    <ul class="breadcrumb ">
                        <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="zmdi zmdi-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{route('students')}}">Etudiants</a></li>
                        <li class="breadcrumb-item active">Modifier informations</li>
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
                    <div class="header">
                        <h2><strong>Modifier</strong> les infos pour <span class="text-uppercase"><b> @if($etudiant->username) {{$etudiant->username}} @else {{$etudiant->nom}} {{$etudiant->prenoms}} @endif </b></span></h2>
                    </div>
                    <div class="body">
                        <form action="{{route('updateEtudiant')}}" method="POST" enctype="multipart/form-data">
                            {{csrf_field()}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Erreur de modification :</strong>
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
                            <div class="alert alert-danger m-2 text-center" id="imageLourde" style="display: none">
                                Photo trop lourde (> 3Mb)
                            </div>
                            <div class="alert alert-danger m-2 text-center" id="typeImage" style="display: none">
                                Nous acceptons uniquement les images jpeg/jpg ou png
                            </div>
                            <div class="row clearfix">
                                <div class="col-12 text-center mb-4 photo pt-2 img-upload">
                                    <label for="img_file_up">
                                        <img id="img_prv" @if($etudiant->photo) src="{{asset('assets/images/etudiants/photos/'.$etudiant->photo)}}" @else src="{{asset('assets/images/avatar.png')}}" @endif alt="photo" class="user_pic rounded img-raised" style="height:150px" />
                                    </label>
                                    <input name="photo" id="img_file_up" type="file" accept=".jpg,.jpeg,.png"/>
                                </div>
                                <input id="id" name="id" type="hidden" value="{{$etudiant->id}}">
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="font-weight-bold" for="nom">Nom</label>
                                        <input id="nom" name="nom" required="true" type="text" class="form-control" value="{{ $etudiant->nom }}">
                                        @error('nom')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="font-weight-bold" for="prenoms">Prénom(s)</label>
                                        <input id="prenoms" name="prenoms" required="true" type="text" class="form-control" value="{{ $etudiant->prenoms }}">
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
                                        <input required="true" type="text" id="datenais" name="datenais" class="form-control datetimepicker" placeholder="Cliquer pour choisir la date..." value="{{ date('d-m-Y', strtotime($etudiant->datenais)) }}">
                                        @error('datenais')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12 mt-2">
                                    <div class="form-group">
                                        <label class="font-weight-bold" for="niveau">Niveau d'étude</label>
                                        <input id="niveau" name="niveau" required="true" type="text" class="form-control" value="{{ $etudiant->niveau }}">
                                        @error('niveau')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12 mt-2">
                                    <div class="form-group">
                                        <label class="font-weight-bold" for="numCarteEtud">Numéro de carte étudiant</label>
                                        <input id="numCarteEtud" name="numCarteEtud" required="true" type="text" class="form-control" value="{{ $etudiant->numCarteEtud }}">
                                        @error('numCarteEtud')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12 mt-2">
                                    <div class="form-group">
                                        <label class="font-weight-bold" for="email">E-mail</label>
                                        <input id="email" name="email" required="true" type="email" class="form-control" value="{{ $etudiant->email }}">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12 mt-2">
                                    <div class="form-group">
                                        <label class="font-weight-bold" for="telephone">Numéro de téléphone</label>
                                        <input id="telephone" name="telephone" required="true" type="tel" class="form-control" value="{{ $etudiant->telephone }}">
                                        @error('telephone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                @if($utilisateur->role->libelle == 'ROOT' || $utilisateur->role->libelle == 'ADMIN' || $utilisateur->role->libelle == 'SUPPORT')
                                <div class="col-lg-4 col-md-12 mt-4">
                                    <div class="checkbox">
                                        <input id="access" name="access" type="checkbox" @if($etudiant->access) checked @endif>
                                        <label class="font-weight-bold" for="access">Peut gérer la plateforme ?</label>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12 mt-2 accessOk">
                                    <div class="form-group">
                                        <label class="font-weight-bold" for="username">Nom d'utilisateur</label>
                                        <input id="username" name="username" type="text" class="form-control" value="@if($etudiant->username){{$etudiant->username}}@endif">
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
                                                <option value="{{$role->id}}" @if($etudiant->roleID == $role->id) selected @endif>{{$role->libelle}}</option>
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
</section>


@include('layout/javascript')

<script>
    $(document).ready(function () {
        if ($('#access').is(':checked')) {
                $('.accessOk').show();
            } else {
                $('.accessOk').hide();
            }
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
                    $('#typeImage').css('display', 'block');
                    setTimeout(function () {
                        $('#typeImage').css('display', 'none');
                    }, 5000);
                }
                else{
                    if(this.files[0].size > 3145728){
                        $('#imageLourde').css('display', 'block');;
                        setTimeout(function () {
                            $('#imageLourde').css('display', 'none');
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
                    $('#imageLourde').css('display', 'block');
                        setTimeout(function () {
                            $('#imageLourde').css('display', 'none');
                        }, 5000);
                }
            });

    });
</script>
</body>
</html>