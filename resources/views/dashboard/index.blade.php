

<x-app-layout>
    <!-- Page Title Starts -->

    <x-page-title header="Dashboard Analítico" />

    @section('title', 'Dashboard analítico | Inusittá')

    @vite(['resources/js/custom/analytics.js',])

    <!-- Page Title Ends -->

    @if(session('status'))
  <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded shadow-lg z-50" role="alert">
    <p>{{ session('status') }}</p>
  </div>
  @endif


    <div class="space-y-6">
        <!-- Overview Section Start -->
        <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
            <!-- Product Views  -->
            <div class="card">
                <div class="card-body flex items-center gap-4">
                    <div
                        class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-primary-500 bg-opacity-20 text-primary-500">
                        <i data-feather="box" class="text-3xl"></i>
                    </div>
                    <div class="flex flex-1 flex-col gap-1">
                        <p class="text-sm tracking-wide text-slate-500">Total documentos mapeados</p>
                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                            <h4>{{ $totalDocuments }}</h4>
                            <span class="flex items-center text-xs font-medium text-success-500"><i class="h-3 w-3"
                                    stroke-width="3px" data-feather="arrow-up-right"></i>2.2%</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Product Sold  -->
            <!-- Likes  -->
            <div class="card">
                <div class="card-body flex items-center gap-4">
                    <div
                        class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-warning-500 bg-opacity-20 text-warning-500">
                        <i class="ti ti-hand-click text-3xl"></i>
                    </div>
                    <div class="flex flex-1 flex-col gap-1">
                        <p class="text-sm tracking-wide text-slate-500">Total documentos visualizados</p>
                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                            <h4>{{ $totalClicks }}</h4>
                            <span class="flex items-center text-xs font-medium text-success-500">
                                <i class="h-3 w-3" stroke-width="3px" data-feather="arrow-up-right"></i> 1.2%</span>
                        </div>
                    </div>
                </div>
            </div>
              <!-- Likes  -->
            <div class="card">
                <div class="card-body flex items-center gap-4">
                    <div
                        class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-success-500 bg-opacity-20 text-success-500">
                        <i class="bx bx-dollar-circle text-3xl"></i>
                    </div>
                    <div class="flex flex-1 flex-col gap-1">
                        <p class="text-sm tracking-wide text-slate-500">Total Setores</p>
                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                            <h4>{{ number_format($totalSetores, 0, ',', '.') }}</h4>
                            <span class="flex items-center text-xs font-medium text-danger-500">
                                <i class="h-3 w-3" stroke-width="3px" data-feather="arrow-down-left"></i> 0.5%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conversation Rate  -->
            <div class="card">
                <div class="card-body flex items-center gap-4">
                    <div
                        class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-info-500 bg-opacity-20 text-info-500">
                        <i class="ti ti-users-group text-3xl"></i>
                    </div>
                    <div class="flex flex-1 flex-col gap-1">
                        <p class="text-sm tracking-wide text-slate-500">Total Usuários</p>
                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                            <h4>{{ $totalUsers }}</h4>
                            <span class="flex items-center text-xs font-medium text-success-500">
                                <i class="h-3 w-3" stroke-width="3px" data-feather="arrow-up-right"></i> 3.2%</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Overview Section End -->

        <!-- Store Analytics, Active Users, Sales By Location, Top & Most Viewed Product Section Start  -->
        <section class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
            <!-- Store Analytics -->
            <div class="card order-1 md:col-span-2">
                <div class="card-body">
                    <!-- Chart wrap -->
                    <div id="chart-wrap" class="flex flex-col justify-between">
                        <div class="flex flex-wrap items-center justify-between gap-3 md:gap-0">
                            <!-- Chart Title  -->
                            <h6>Ultimos Login</h6>
                            <!-- Legends  -->
                            <div id="store-analytics-chart-legend" class="flex items-center gap-4">
                                <label for="visitors">
                                    <input type="checkbox" id="visitors" class="hidden" checked value="Visitors" />
                                    <div class="flex items-center gap-1">
                                        <div class="h-[10px] w-[10px] rounded-full bg-primary-500"></div>
                                        <p class="text-sm font-medium text-slate-600 dark:text-slate-300">Login </p>
                                    </div>
                                </label>


                            </div>
                            <!-- Select By Chart -->
                            <select class="select select-sm w-full md:w-32">
                                <option value="1">Anual</option>
                                <option value="2">Mês</option>
                            </select>
                        </div>
                        <!-- Chart  -->
                        <div id="store-analytics-chart" class="-mx-4"></div>
                        <script>
                        window.activeUsersChartData = @json($activeUsersChartData);
                        window.loginChartData = @json($loginChartData);
                    </script>
                    </div>
                </div>
            </div>
            <!-- Active Users -->
            <div class="card order-3 col-span-1 xl:order-2">
                <div class="card-body flex flex-col items-center justify-between">
                    <!-- Header  -->
                    <div class="flex w-full justify-between">
                        <h6>Usuarios Ativos</h6>
                        <div class="dropdown" data-placement="bottom-end">
                            <div class="dropdown-toggle">
                                <i class="ti ti-dots-vertical text-lg text-slate-500"></i>
                            </div>
                            <div class="dropdown-content w-[160px]">
                                <ul class="dropdown-list">
                                    <li class="dropdown-list-item">
                                        <a href="javascript:void(0)" class="dropdown-link gap-2"> Action </a>
                                    </li>
                                    <li class="dropdown-list-item">
                                        <a href="javascript:void(0)" class="dropdown-link gap-2"> Another Action </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Chart  -->
                    <div id="donutChart" class="w-full"></div>
                    <script>
            window.activeUsersChartData = @json($activeUsersChartData);
        </script>
                </div>
            </div>
            <!-- Sales By Location  -->

            <!-- Top Selling & Most Viewed Product  -->

        </section>
        <!-- Store Analytics, Active Users, Sales By Location, Top & Most Viewed Product Section End  -->

<div class="card order-4 md:col-span-2">
    <div class="card-body">
        <!-- Header -->
        <div class="flex w-full justify-between mb-4">
            <h6>Documentos por Setor e Macro</h6>
        </div>
        <!-- Chart -->
        <div id="macroSectorChart" class="w-full h-[350px]"></div>

        <script>
            window.macroSectorChartData = @json($macroSectorChartData);
        </script>
    </div>
</div>

        <section class="grid grid-cols-1 gap-6 lg:grid-cols-2">
    <div class="card">
        <div class="card-body">
            <h6 class="font-semibold text-slate-700 mb-2">Top 5 Setores com Mais Documentos</h6>

            <div class="space-y-4">
                @foreach ($totalPorSetor as $setor)
                    @php
                        $percentual = min(($setor->total / 200) * 100, 100); // Exemplo de meta de 200 por setor
                        $corBarra = $percentual < 50 ? 'bg-slate-400' : ($percentual < 75 ? 'bg-warning-500' : 'bg-success-500');
                    @endphp

                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm text-slate-600">{{ $setor->name }}</span>
                            <span class="text-sm font-medium text-slate-800">{{ $setor->total }}</span>
                        </div>
                        <div class="h-3 rounded bg-slate-200 overflow-hidden">
                            <div class="h-full {{ $corBarra }}" style="width: {{ $percentual }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>




        <!-- Customer Satisfaction & Top Customers Section Start -->
        <section class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Customer Satisfaction  -->
            <div class="card">
                <div class="card-body">
                    <!-- Header  -->
                    <div class="flex w-full justify-between">
                        <h6>Top 5 Macros com Mais Documentos</h6>
                        <div class="dropdown" data-placement="bottom-end">
                            <div class="dropdown-toggle">
                                <i class="ti ti-dots-vertical text-lg text-slate-500"></i>
                            </div>
                            <div class="dropdown-content w-[160px]">
                                <ul class="dropdown-list">
                                    <li class="dropdown-list-item">
                                        <a href="javascript:void(0)" class="dropdown-link gap-2"> Action </a>
                                    </li>
                                    <li class="dropdown-list-item">
                                        <a href="javascript:void(0)" class="dropdown-link gap-2"> Another Action </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    

                    <!-- Performance Score Progress -->
                    <div class="mt-4 flex w-full gap-[6px]">
                       
                       
                    </div>
                    <br />
                    <div class="space-y-8 overflow-x-auto">
                        <!-- Excellent -->
                        <div class="mt-4 space-y-4">
    @php
        $metaPorMacro = 200;
    @endphp

    @foreach ($totalPorMacro as $macro)
                    @php
                        $percentual = min(($macro->documents_count / $metaPorMacro) * 100, 100);

                        // Cores diferentes conforme atingimento da meta
                        if ($percentual < 50) {
                            $corBarra = 'bg-success-500';
                        } elseif ($percentual < 75) {
                            $corBarra = 'bg-info-500';
                        } else {
                            $corBarra = 'bg-success-500';
                        }
                    @endphp

                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm text-slate-600 truncate">{{ $macro->name }}</span>
                            <span class="text-sm font-medium text-slate-800">{{ $macro->documents_count }} / {{ $metaPorMacro }}</span>
                        </div>
                        <div class="h-3 rounded bg-slate-200 overflow-hidden">
                            <div class="h-full {{ $corBarra }}" style="width: {{ $percentual }}%"></div>
                        </div>
                    </div>
                @endforeach
</div>
                    </div>
                </div>
            </div>
            <!-- Top Customers  -->
            <!-- Usuários Mais Ativos -->
<div class="card">
    <div class="card-body flex h-full flex-col justify-between gap-2">
        <!-- Header  -->
        <div class="flex w-full justify-between">
            <h6>Usuários Mais Ativos</h6>
            <div class="dropdown" data-placement="bottom-end">
                <div class="dropdown-toggle">
                    <i class="ti ti-dots-vertical text-lg text-slate-500"></i>
                </div>
                <div class="dropdown-content w-[160px]">
                    <ul class="dropdown-list">
                        <li class="dropdown-list-item">
                            <a href="javascript:void(0)" class="dropdown-link gap-2">Exportar CSV</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tabela de Usuários -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Logins</th>
                        <th>Último Acesso</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuariosMaisAtivos as $item)
                        @php
                            $user = \App\Models\User::find($item->user_id);
                        @endphp
                        @if ($user)
                            <tr>
                                <td class="whitespace-nowrap">
                                    <div class="flex gap-2 items-center">
                                         <div class="avatar avatar-circle">
                    <img class="avatar-img" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/avatar1.png') }}" 
                alt="{{ $user->name }}" />
                  </div>
                                        <p class="text-sm font-medium">{{ $user->name }}</p>
                                    </div>
                                </td>
                                <td class="text-sm text-center">{{ $item->total }}</td>
                                <td class="text-sm text-slate-400">{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

        </section>
        <!-- Customer Satisfaction & Top Customers Section End -->
    </div>
</x-app-layout>
