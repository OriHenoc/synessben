<aside id="minileftbar" class="minileftbar">
    <ul class="menu_list">
        <li>
            <a href="javascript:void(0);" class="bars"></a>
            <a class="navbar-brand" href="{{route('home')}}">
                <img src="{{asset('assets/images/logo.svg')}}" alt="SYNESS">
            </a>
        </li>    
        <li>
            <a href="javascript:void(0);" class="menu-sm"><i class="zmdi zmdi-swap"></i></a>
        </li>     
        <li>
            <a href="javascript:void(0);" class="fullscreen" data-provide="fullscreen"><i class="zmdi zmdi-fullscreen"></i></a>
        </li>
        <li class="power">           
            <a href="{{route('deconnexion')}}" class="mega-menu"><i class="zmdi zmdi-power"></i></a>
        </li>
    </ul>    
</aside>
<aside class="right_menu">
    <div id="leftsidebar" class="sidebar">
        <div class="menu">
            <ul class="list">
                <li>
                    <div class="user-info m-b-20">
                        <div class="image">
                            <a href="{{route('profil')}}">
                                <img @if($utilisateur->photo) src="{{asset('assets/images/etudiants/photos/'.$utilisateur->photo)}}" @else src="{{asset('assets/images/avatar.png')}}" @endif alt="Utilisateur">
                            </a>
                        </div>
                        <div class="detail">
                            <h6>{{$utilisateur->username}}</h6>
                            <p class="m-b-0">@if($utilisateur->role->libelle == 'SUPERVISEUR') LE SGN @else {{$utilisateur->role->libelle}} @endif</p>                         
                        </div>
                    </div>
                </li>
                <li class="header">GESTION</li>
                <li class="@if($menu=='Tableau de bord') active open @endif">
                    <a href="{{route('home')}}">
                        <i class="zmdi zmdi-home"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>
                @if($utilisateur->role->libelle !== 'ETUDIANT')
                <li class="@if($menu=='Etudiants') active open @endif">
                    <a href="{{route('students')}}" class="menu-toggle">
                        <i class="zmdi zmdi-accounts"></i>
                        <span>Etudiants</span>
                        <span class="badge badge-success float-right">{{count($etudiants)}}</span>
                    </a>
                </li>
                @endif
                @if($utilisateur->role->libelle == 'ROOT' || $utilisateur->role->libelle == 'ADMIN' || $utilisateur->role->libelle == 'COMPTABLE')
                <li class="@if($menu=='Paiements') active open @endif">
                    <a href="{{route('payments')}}">
                        <i class="zmdi zmdi-money"></i>
                        <span>Paiements</span>
                        <span class="badge badge-default float-right">{{count($paiements)}}</span>
                    </a>
                </li>
                @endif
                @if($utilisateur->role->libelle == 'ROOT' || $utilisateur->role->libelle == 'ADMIN' || $utilisateur->role->libelle == 'SUPPORT' || $utilisateur->role->libelle == 'SUPERVISEUR')
                <li class="header">PARAMETRES</li>
                <li class="@if($menu=='Utilisateurs') active open @endif">
                    <a href="{{route('users')}}" class="menu-toggle">
                        <i class="zmdi zmdi-account"></i>
                        <span>Utilisateurs</span>
                        <span class="badge badge-warning float-right">{{count($utilisateurs)}}</span>
                    </a>
                </li>
                @if($utilisateur->role->libelle == 'ROOT' || $utilisateur->role->libelle == 'SUPPORT')
                <li><a href="javascript:void(0);" class="menu-toggle">
                    <i class="zmdi zmdi-grid"></i>
                    <span>Rôles</span>
                    <span class="badge badge-info float-right">{{count($roles)}}</span>
                </a>
                </li>            
                <li><a href="javascript:void(0);" class="menu-toggle">
                    <i class="zmdi zmdi-delicious"></i>
                    <span>Permissions</span>
                    <span class="badge badge-default float-right">6</span>
                </a>
                </li>
                @endif               
                <li><a href="javascript:void(0);" class="menu-toggle">
                    <i class="zmdi zmdi-settings"></i>
                    <span>Configurations</span>
                    <span class="badge badge-default float-right">3</span>
                </a>
                </li>  
                @endif          
            </ul>
        </div>
    </div>
</aside>