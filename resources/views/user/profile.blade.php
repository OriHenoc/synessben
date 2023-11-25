<!DOCTYPE html>
<html lang="fr">
@include('layout/head')
<body class="theme-black">
@include('layout/nav')

<section class="content profile-page">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <h2>Mon Profil</h2>
                    <ul class="breadcrumb padding-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="zmdi zmdi-home"></i></a></li>
                        <li class="breadcrumb-item acve">Profil</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="body bg-dark profile-header">
                        <div class="row">
                            <div class="col-12">
                                <div class="photo pt-2 img-upload">
                                    <label for="img_file_up">
                                        <img id="img_prv" @if($utilisateur->photo) src="{{asset('assets/images/etudiants/photos/'.$utilisateur->photo)}}" @else src="{{asset('assets/images/avatar.png')}}" @endif style="height: 150px" class="user_pic rounded img-raised" alt="Utilisateur">
                                    </label>
                                    <input name="photo" id="img_file_up" data-carte="{{$utilisateur->numCarteEtud}}" type="file" accept=".jpg,.jpeg,.png"/>
                                </div>
                                <div class="detail">
                                    <div class="u_name">
                                        <h4>Bienvenue sur le profil de <strong class="text-uppercase">{{$utilisateur->username}}</strong> </h4>
                                        <span>Rôle : {{$utilisateur->role->libelle}}</span>
                                    </div>
                                    <div style="height:100px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ul class="nav nav-tabs profile_tab">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#infos">Mes infos</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#security">Ma sécurité</a></li>
                    </ul>
                    <div class="alert alert-success m-2 text-center" id="photoOk" style="display: none">
                        Photo mise à jour !
                    </div>
                    <div class="alert alert-danger m-2 text-center" id="imageLourde" style="display: none">
                        Photo trop lourde (> 3Mb)
                    </div>
                    <div class="alert alert-danger m-2 text-center" id="typeImage" style="display: none">
                        Nous acceptons uniquement les images jpeg/jpg ou png
                    </div>
                </div>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="infos">
                        <div class="card">
                            <div class="header">
                                <h2><strong>Informations</strong> de mon compte</h2>
                                @if(Session::has('success'))
                                    <div class="alert alert-success m-2 text-center">
                                        {{ Session::get('success') }}
                                        @php
                                            Session::forget('success');
                                        @endphp
                                    </div>
                                @endif
                            </div>
                            <div class="body">
                                <form action="{{route('updateEtudiant')}}" method="POST">
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
                                    <div class="row clearfix">
                                        <input id="id" name="id" type="hidden" value="{{$utilisateur->id}}">
                                        <input id="monProfil" name="monProfil" type="hidden" value="oui">
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="nom">Nom</label>
                                                <input id="nom" name="nom" required="true" type="text" class="form-control" value="{{ $utilisateur->nom }}">
                                                @error('nom')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="prenoms">Prénom(s)</label>
                                                <input id="prenoms" name="prenoms" required="true" type="text" class="form-control" value="{{ $utilisateur->prenoms }}">
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
                                                <input required="true" type="text" id="datenais" name="datenais" class="form-control datetimepicker" placeholder="Cliquer pour choisir la date..." value="{{ $utilisateur->datenais }}">
                                                @error('datenais')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 mt-2">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="niveau">Niveau d'étude</label>
                                                <input id="niveau" name="niveau" required="true" type="text" class="form-control" value="{{ $utilisateur->niveau }}">
                                                @error('niveau')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 mt-2">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="numCarteEtud">Numéro de carte étudiant</label>
                                                <input id="numCarteEtud" name="numCarteEtud" required="true" type="text" class="form-control" value="{{ $utilisateur->numCarteEtud }}">
                                                @error('numCarteEtud')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 mt-2 accessOk">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="username">Nom d'utilisateur</label>
                                                <input id="username" name="username" type="text" class="form-control" value="@if($utilisateur->username){{$utilisateur->username}}@endif">
                                                @error('username')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 mt-2">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="email">E-mail</label>
                                                <input id="email" name="email" required="true" type="email" class="form-control" value="{{ $utilisateur->email }}">
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 mt-2">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="telephone">Numéro de téléphone</label>
                                                <input id="telephone" name="telephone" required="true" type="tel" class="form-control" value="{{ $utilisateur->telephone }}">
                                                @error('telephone')
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
                    <div role="tabpanel" class="tab-pane" id="security">
                        <div class="card">
                            <div class="header">
                                <h2><strong>Changer</strong> mon mot de passe</h2>
                                @if(Session::has('success'))
                                    <div class="alert alert-success m-2 text-center">
                                        {{ Session::get('success') }}
                                        @php
                                            Session::forget('success');
                                        @endphp
                                    </div>
                                @endif
                            </div>
                            <div class="body">
                                <form action="{{route('modifierMotDePasse')}}" method="POST">
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
                                    <div class="mt-3">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="passwd">Mot de passe actuel</label>
                                            <input id="passwd" required="true" name="passwd" type="password" class="form-control" placeholder="Mot de passe actuel">
                                            @error('passwd')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="newPasswd">Nouveau mot de passe</label>
                                            <input id="newPasswd" required="true" name="newPasswd" type="password" class="form-control" placeholder="Nouveau mot de passe">
                                            @error('newPasswd')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="confNewpasswd">Confirmer le nouveau mot de passe</label>
                                            <input id="confNewpasswd" required="true" name="confNewpasswd" type="password" class="form-control" placeholder="Confirmer le nouveau mot de passe">
                                            @error('confNewpasswd')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <button class="btn btn-info btn-round" type="submit">Enregistrer</button>   
                                </form>                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('layout/javascript')

<script>
    $(document).ready(function () {

        @if($errors->has('passwd'))
            $('#infos').removeClass('active');
            $('#security').addClass('active');
            $('a[href="#infos"]').removeClass('active');
            $('a[href="#security"]').addClass('active');
        @endif

        @if($errors->has('newPasswd'))
            $('#infos').removeClass('active');
            $('#security').addClass('active');
            $('a[href="#infos"]').removeClass('active');
            $('a[href="#security"]').addClass('active');
        @endif

        @if($errors->has('confNewPasswd'))
            $('#infos').removeClass('active');
            $('#security').addClass('active');
            $('a[href="#infos"]').removeClass('active');
            $('a[href="#security"]').addClass('active');
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

                        //upload
                        var c = $(this).data('carte');
                            var postData = new FormData();
                            postData.append('photo', this.files[0])
                            postData.append('numCarteEtud', c)
                            postData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                            var url = "{{ route('changePP') }}"
                            $.ajax({
                                method: 'POST',
                                contentType: false,
                                url: url,
                                data: postData,
                                processData: false,
                                success: function(){
                                    $('#photoOk').css('display', 'block');;
                                    setTimeout(function () {
                                        $('#photoOk').css('display', 'none');
                                    }, 5000);
                                    location.reload(true);
                                }
                            })
                    }
                    
                }
            })

            $('#btnuploadphoto').click(function() {
                $("#photo_file_up").click();
            })

            $('#photo_file_up').change(function() {
                if(this.files[0].size > 3145728){
                    $('#imageLourde').css('display', 'block');;
                        setTimeout(function () {
                            $('#imageLourde').css('display', 'none');
                        }, 5000);
                }
                else{
                  $('#photoupload').submit();  
                }
            });


    });
</script>

</body>
</html>