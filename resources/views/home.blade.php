<!DOCTYPE html>
<html lang="fr">
@include('layout/head')
<body class="theme-black">
@include('layout/nav')

<section class="content home">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <h2>Tableau de bord</h2>
                    <ul class="breadcrumb padding-0">
                        <li class="breadcrumb-item"><a href="#"><i class="zmdi zmdi-home"></i></a></li>
                        <li class="breadcrumb-item active">Tableau de bord</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-3 col-md-6">
                <div class="card text-center">
                    <div class="body">
                        <p class="m-b-20"><i class="zmdi zmdi-money zmdi-hc-3x col-amber"></i></p>
                        <span>Revenu Total</span>
                        <h3 class="m-b-10"><span id="revenu">calcul ...</span></h3>
                        <small class="text-muted">(Montant entré en caisse)</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card text-center">
                    <div class="body">
                        <p class="m-b-20"><i class="zmdi zmdi-assignment zmdi-hc-3x col-blue"></i></p>
                        <span>Nombre d'inscrits</span>
                        <h3 class="m-b-10 number count-to" data-from="0" data-to="{{count($etudiants)}}" data-speed="2000" data-fresh-interval="700">0</h3>
                        <small class="text-muted">(Etudiants enregistrés)</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card text-center">
                    <div class="body">
                        <p class="m-b-20"><i class="zmdi zmdi-check-circle zmdi-hc-3x text-success"></i></p>
                        <span>Ayant soldé</span>
                        <h3 class="m-b-10 number count-to" data-from="0" data-to="{{count($etudiantsSoldes)}}" data-speed="2000" data-fresh-interval="700">0</h3>
                        <small class="text-muted">(ceux qui ont payé la totalité)</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card text-center">
                    <div class="body">
                        <p class="m-b-20"><i class="zmdi zmdi-shopping-basket zmdi-hc-3x text-danger"></i></p>
                        <span>Paiements en cours</span>
                        <h3 class="m-b-10 number count-to" data-from="0" data-to="{{count($etudiantsNonSoldes)}}" data-speed="2000" data-fresh-interval="700">0</h3>
                        <small class="text-muted">(ceux qui ont payé partiellement)</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-6 col-lg-6">
                <div class="card">
                    <div class="header">
                        <h2><strong>Repartition</strong> Paiement <small>La situation des paiements</small></h2>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu slideUp">
                                    <li><a class="boxs-close">cacher</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body align-center">
                        <div class="row pb-3">
                            <div class="col-6">
                                <h4 class="margin-0">
                                    <i class="material-icons text-danger">lens</i>
                                </h4>
                                <p>Paiement partiel</p>
                            </div>
                            <div class="col-6">
                                <h4 class="margin-0">
                                    <i class="material-icons text-success">lens</i>
                                </h4>
                                <p>Soldé</p>
                            </div>
                        </div>
                        <div class="sparkline-pie" style="height: 247px;">{{count($etudiantsNonSoldes)}},{{count($etudiantsSoldes)}}</div>
                    </div>     
                </div>
            </div>
            <div class="col-md-6 col-lg-6">
                <div class="card">
                    <div class="header">
                        <h2><strong>Derniers</strong> Inscrits <small>5 derniers étudiants enregistrés</small></h2>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu slideUp">
                                    <li><a class="boxs-close">Cacher</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <ul class="follow_us list-unstyled">
                            @foreach($fiveLast as $etudiant)
                            <li class="online">
                                <a href="{{route('students')}}">
                                    <div class="media">
                                        <img class="media-object" @if($etudiant->photo) src="{{asset('assets/images/etudiants/photos/'.$etudiant->photo)}}" @else src="{{asset('assets/images/avatar.png')}}" @endif alt="Etudiant">
                                        <div class="media-body">
                                            <span class="name">@if($etudiant->username) {{$etudiant->username}} @else {{$etudiant->nom}} {{$etudiant->prenoms}} @endif</span>
                                            <span class="message">{{$etudiant->numCarteEtud}}</span>
                                            <span class="badge badge-outline status"></span>
                                        </div>
                                    </div>
                                </a>                            
                            </li>
                            @endforeach                    
                        </ul>
                    </div>                   
                </div>
            </div>
        </div>                
    </div>
</section>

@include('layout/javascript')

<script>

$(document).ready(function() {

    var rev = new Intl.NumberFormat('fr-FR').format({{$revenu}});
    
    $('#revenu').text(rev+' F').delay(2000).fadeIn(1000);
    });

</script>

</body>
</html>