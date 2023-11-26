<!DOCTYPE html>
<html lang="fr">
@include('layout/head')
<body class="theme-black">

<div class="authentication">
    <div class="container">
        <div class="col-md-12 content-center">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="company_detail">
                        <h4 class="logo"><img src="assets/images/logo.svg" alt="logo"> SYNESS-BEN</h4>
                        <h3>Outil <strong>Puissant</strong> de gestion pour le SYNESS</h3>
                        <p class="text-justify">
                            SYNESS-BEN est une idée originale du bureau exécutif national 
                            avec à sa tête le NL DOS LARGE.
                        </p>                        
                        <div class="footer">
                            <hr>
                            <ul>
                                <li>Powered by <a href="https://committeam.com" target="_blank">COMMIT TEAM</a></li>
                            </ul>
                        </div>
                    </div>                    
                </div>
                <div class="col-lg-5 col-md-12 offset-lg-1">
                    @if(Session::has('success'))
                        <div class="alert alert-success m-2 text-center">
                            {{ Session::get('success') }}
                            @php
                                Session::forget('success');
                            @endphp
                        </div>
                    @endif
                    <div class="card-plain">
                        <div class="header">
                            <h5>Connexion</h5>
                        </div>
                        <form class="form" action="{{route('connect')}}" method="POST">
                            {{csrf_field()}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Erreur de connexion :</strong>
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
                            <div class="input-group">
                                <input type="email" id="email" required="true" name="email" class="form-control" placeholder="E-mail" value="{{ old('email') }}">
                                <span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-group">
                                <input type="password" id="passwd" required="true" name="passwd" placeholder="Mot de passe" class="form-control" />
                                <span class="input-group-addon"><i class="zmdi zmdi-lock"></i></span>
                                @error('passwd')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div> 
                            <div class="footer">
                                <button class="btn btn-primary btn-round btn-block">SE CONNECTER</button>
                            </div>
                        </form>
                        <a href="#" class="link">Mot de passe oublié ?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layout/javascript')

<script>
    $(document).ready(function () {
        @if($errors->any())
            setTimeout(function () {
                $('.alert-danger').fadeOut('slow');
            }, 2000);
            setTimeout(function () {
                $('.text-danger').fadeOut('slow');
            }, 3500);
        @endif

        setTimeout(function () {
            $('.alert-danger').fadeOut('slow');
        }, 2000);

        setTimeout(function () {
            $('.alert-success').fadeOut('slow');
        }, 2000);
    });
</script>

</body>
</html>