<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Librería') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap 5 + Datatables (estilos) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.8/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/3.1.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/4.0.2/css/buttons.bootstrap5.min.css" rel="stylesheet">

    <!-- FontAwesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts (paso 38: se comenta para no cargar Bootstrap dos veces)
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    -->
</head>

<body class="bg-light">
    <div id="app" class="d-flex flex-column min-vh-100">

        <!-- ===================== MENÚ ===================== -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm sticky-top">
            <div class="container">

                <!-- Enlace de Inicio / Panel de Control -->
                <a class="navbar-brand fw-bold px-3 py-1 rounded-pill {{ request()->routeIs('home') ? 'bg-primary text-white shadow-sm' : 'text-primary' }}" href="{{ Auth::check() ? route('home') : url('/') }}">
                    <i class="fas fa-home me-1"></i>{{ __('Inicio') }}
                </a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Menú del sistema (solo con sesión iniciada) -->
                    <ul class="navbar-nav me-auto">
                        @auth
                        <!-- Catálogos -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('productos.*') ? 'active fw-semibold' : '' }}" href="{{ route('productos.index') }}">
                                <i class="fas fa-book me-1"></i>{{ __('Productos') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('categorias.*') ? 'active fw-semibold' : '' }}" href="{{ route('categorias.index') }}">
                                <i class="fas fa-tags me-1"></i>{{ __('Categorías') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('autores.*') ? 'active fw-semibold' : '' }}" href="{{ route('autores.index') }}">
                                <i class="fas fa-user-edit me-1"></i>{{ __('Autores') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('clientes.*') ? 'active fw-semibold' : '' }}" href="{{ route('clientes.index') }}">
                                <i class="fas fa-users me-1"></i>{{ __('Clientes') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('empleados.*') ? 'active fw-semibold' : '' }}" href="{{ route('empleados.index') }}">
                                <i class="fas fa-id-badge me-1"></i>{{ __('Empleados') }}
                            </a>
                        </li>

                        <!-- Procesos -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('ventas.*') ? 'active fw-semibold' : '' }}" href="{{ route('ventas.index') }}">
                                <i class="fas fa-cash-register me-1"></i>{{ __('Ventas') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('detalle-ventas.*') ? 'active fw-semibold' : '' }}" href="{{ route('detalle-ventas.index') }}">
                                <i class="fas fa-list me-1"></i>{{ __('Detalle') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('pagos.*') ? 'active fw-semibold' : '' }}" href="{{ route('pagos.index') }}">
                                <i class="fas fa-credit-card me-1"></i>{{ __('Pagos') }}
                            </a>
                        </li>
                        @endauth
                    </ul>

                    <!-- Lado derecho: sesión -->
                    <ul class="navbar-nav ms-auto align-items-center">
                        @guest
                            @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link fw-semibold" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt me-1"></i>{{ __('Iniciar sesión') }}
                                </a>
                            </li>
                            @endif

                            @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="btn btn-outline-primary btn-sm rounded-pill px-3 ms-2 fw-semibold" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus me-1"></i>{{ __('Registrarse') }}
                                </a>
                            </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle fw-semibold text-dark d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <span class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center me-2 shadow-sm fw-bold px-2 py-1">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </span>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>{{ __('Cerrar sesión') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- ===================== CONTENIDO ===================== -->
        <main class="py-4 flex-grow-1">
            @yield('content')
        </main>

        <!-- ===================== PIE DE PÁGINA ===================== -->
        <footer class="bg-white text-muted py-4 mt-auto border-top shadow-sm">
            <div class="container text-center">
                <p class="mb-1 fw-semibold text-secondary">
                    <i class="fas fa-book-open text-primary me-1"></i>
                    Sistema de Librería | Desarrollado usando Laravel y MySQL
                </p>
                <p class="mb-1 small">
                    Curso: BackEnd Developer Web | Tercer Semestre | <strong>SENATI</strong>
                </p>
                <p class="mb-0 small">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Librería') }}. Todos los derechos reservados.
                </p>
            </div>
        </footer>

    </div><!-- Fin de #app -->

    <!-- ===================== SCRIPTS ===================== -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.8/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/3.1.4/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/3.1.4/js/dataTables.bootstrap5.min.js"></script>

    <!-- Exportar: Excel (JSZip), PDF (pdfmake), CSV e Imprimir (Buttons) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/4.0.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/4.0.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/4.0.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/4.0.2/js/buttons.print.min.js"></script>

    <script>
        // Datatables: se activa solo en las páginas que tienen una tabla con id="example"
        document.addEventListener('DOMContentLoaded', function () {
            const tabla = document.querySelector('#example');
            if (!tabla) return;

            // Título de los archivos exportados (viene de data-titulo en cada index)
            const titulo = tabla.dataset.titulo || document.title;
            const fecha  = new Date().toLocaleDateString('es-PE');

            // Exportar todas las columnas MENOS la última (Acciones)
            const columnas = { columns: ':not(:last-child)' };

            new DataTable(tabla, {
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                order: [],
                columnDefs: [
                    { targets: -1, orderable: false, searchable: false }   // columna Acciones
                ],

                // Distribución: botones arriba, luego "Mostrar" y "Buscar"
                layout: {
                    top2Start: {
                        buttons: {
                            dom: { button: { className: 'btn btn-sm rounded-pill px-3 me-2 fw-semibold shadow-sm' } },
                            buttons: [
                                {
                                    extend: 'excelHtml5',
                                    text: '<i class="fas fa-file-excel me-1"></i>Excel',
                                    className: 'btn-success',
                                    title: titulo,
                                    exportOptions: columnas
                                },
                                {
                                    extend: 'csvHtml5',
                                    text: '<i class="fas fa-file-csv me-1"></i>CSV',
                                    className: 'btn-info text-white',
                                    title: titulo,
                                    exportOptions: columnas
                                },
                                {
                                    extend: 'pdfHtml5',
                                    text: '<i class="fas fa-file-pdf me-1"></i>PDF',
                                    className: 'btn-danger',
                                    title: titulo,
                                    messageTop: 'Generado el ' + fecha,
                                    orientation: 'landscape',
                                    pageSize: 'A4',
                                    exportOptions: columnas
                                },
                                {
                                    extend: 'print',
                                    text: '<i class="fas fa-print me-1"></i>Imprimir',
                                    className: 'btn-dark',
                                    title: titulo,
                                    messageTop: 'Generado el ' + fecha,
                                    exportOptions: columnas
                                }
                            ]
                        }
                    },
                    topStart: 'pageLength',
                    topEnd: 'search'
                },

                language: {
                    search:       'Buscar:',
                    lengthMenu:   'Mostrar _MENU_ registros',
                    info:         'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    infoEmpty:    'No hay registros para mostrar',
                    infoFiltered: '(filtrado de _MAX_ registros en total)',
                    zeroRecords:  'No se encontraron resultados',
                    emptyTable:   'Aún no hay registros',
                    paginate: { first: '«', previous: '‹', next: '›', last: '»' }
                }
            });
        });
    </script>

    <!-- Scripts adicionales de las vistas -->
    @stack('js')
</body>

</html>
