<!DOCTYPE html>
<html lang="fr">
@include('layout/head')
<body class="theme-black">

<div class="authentication">
    <div class="container">
        <div class="col-12 content-center">
            <div class="row">
                <div class="mx-auto">
                    <div class="card-plain">
                        <div class="body widget-user">
                            <div class="row">
                                <div class="text-center mx-auto">
                                    <img class="rounded-circle" style="height: 250px" src="{{asset('assets/images/face.svg')}}" alt="Inconnu">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="m-b-0 text-uppercase">
                                        <b>
                                            Qui êtes-vous ?
                                        </b>
                                    </h5>
                                    <small>Nous ne vous reconnaissons pas parmis les membres de cette plateforme !</small>
                                    <br>
                                    <small class="text-danger">Vous serez redirigé sur google dans 10 secondes...</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layout/javascript')

<script>
    setTimeout(function() {
        window.location.href = document.referrer || 'https://www.google.com';
    }, 10000);
</script>

</body>
</html>