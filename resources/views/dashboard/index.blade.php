<x-app-layout>
    <!-- Page Title Starts -->
     
    <x-page-title header="Dashboard" />

    <!-- Page Title Ends -->

    <div class="space-y-6">
        <!-- Overview Section Start -->
        <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
            <!-- Product Views  -->
            <div class="card">
                <div class="card-body flex items-center gap-4">
                    <div
                        class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-primary-500 bg-opacity-20 text-primary-500">
                        <i class="bx bx-archive text-3xl"></i>

                    </div>
                    <div class="flex flex-1 flex-col gap-1">
                        <p class="text-sm tracking-wide text-slate-500">Total Documentos</p>
                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                            <h4>{{ number_format($totalDocumentos) }}</h4>
                            <span class="flex items-center text-xs font-medium text-success-500"><i class="h-3 w-3"
                                    stroke-width="3px" data-feather="arrow-up-right"></i>2.2%</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Product Sold  -->
            <div class="card">
                <div class="card-body flex items-center gap-4">
                    <div
                        class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-success-500 bg-opacity-20 text-success-500">
                        <i class="ti ti-shopping-cart text-3xl"></i>
                    </div>
                    <div class="flex flex-1 flex-col gap-1">
                        <p class="text-sm tracking-wide text-slate-500">Total Orders</p>
                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                            <h4>5,630</h4>
                            <span class="flex items-center text-xs font-medium text-danger-500">
                                <i class="h-3 w-3" stroke-width="3px" data-feather="arrow-down-left"></i> 0.5%</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Likes  -->
            <div class="card">
                <div class="card-body flex items-center gap-4">
                    <div
                        class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-warning-500 bg-opacity-20 text-warning-500">
                        <i class="ti ti-users text-3xl"></i>
                    </div>
                    <div class="flex flex-1 flex-col gap-1">
                        <p class="text-sm tracking-wide text-slate-500">Total Usuarios</p>
                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                            <h4>{{ number_format($totalUsers) }}</h4>
                            <span class="flex items-center text-xs font-medium text-success-500">
                                <i class="h-3 w-3" stroke-width="3px" data-feather="arrow-up-right"></i> 1.2%</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Conversation Rate  -->
            <div class="card">
                <div class="card-body flex items-center gap-4">
                    <div
                        class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-info-500 bg-opacity-20 text-info-500">
                        <i class="ti ti-receipt-refund text-3xl"></i>
                    </div>
                    <div class="flex flex-1 flex-col gap-1">
                        <p class="text-sm tracking-wide text-slate-500">Total Refunds</p>
                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                            <h4>$20,56</h4>
                            <span class="flex items-center text-xs font-medium text-success-500">
                                <i class="h-3 w-3" stroke-width="3px" data-feather="arrow-up-right"></i> 3.2%</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Overview Section End -->

        <!-- Sales Report, Order Status, Profit, Revenue, Shipping Status Section Start  -->
        <section class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
            <!-- Sales Report -->
            <div class="card col-span-1 md:col-span-2">
                <div class="card-body">
                    <!-- Chart wrap -->
                    <div id="chart-wrap" class="flex flex-col justify-between">
                        <div class="flex flex-wrap items-center justify-between gap-3 md:gap-0">
                            <!-- Chart Title  -->
                            <h6>Sales Report</h6>
                            <!-- Legends  -->
                            <div id="sales-report-chart-legend" class="flex items-center gap-4">
                                <label for="sales">
                                    <input type="checkbox" id="sales" class="hidden" checked value="Sales" />
                                    <div class="flex items-center gap-1">
                                        <div class="h-[10px] w-[10px] rounded-full bg-primary-500"></div>
                                        <p class="text-sm font-medium text-slate-600 dark:text-slate-300">Sales</p>
                                    </div>
                                </label>

                                <label for="profit">
                                    <input type="checkbox" id="profit" class="hidden" checked value="Profit" />
                                    <div class="flex items-center gap-1">
                                        <div class="h-[10px] w-[10px] rounded-full bg-sky-500"></div>
                                        <span
                                            class="text-sm font-medium text-slate-600 dark:text-slate-300">Profit</span>
                                    </div>
                                </label>
                            </div>
                            <!-- Select By Chart -->
                            <select class="select select-sm w-full md:w-32">
                                <option value="1">Yearly</option>
                                <option value="2">Monthly</option>
                                <option value="2">Date & Time</option>
                            </select>
                        </div>
                        <!-- Chart  -->
                        <div id="sales-report-chart" class="-ml-4 -mr-2 sm:-mx-4"></div>
                    </div>
                </div>
            </div>
            <!-- Order Status -->
            <div class="card xl:col-span-1">
                <div class="card-body flex flex-col items-center justify-between">
                    <!-- Header  -->
                    <div class="flex w-full justify-between">
                        <h6>Order Status</h6>
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
                    <div id="order-status-chart" class="w-full"></div>
                </div>
            </div>
            <!-- Profit -->
            <div class="card col-span-1">
                <div class="card-body">
                    <!-- Header  -->
                    <div class="flex w-full justify-between">
                        <h6>Profit Growth</h6>
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
                    <div id="profit-chart" class="-ml-4 -mr-2 sm:-mx-4"></div>
                </div>
            </div>
            <!-- Revenue -->
            <div class="card col-span-1">
                <div class="card-body">
                    <!-- Header  -->
                    <div class="flex w-full justify-between">
                        <h6>Revenue</h6>
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
                    <div id="revenue-chart" class="-ml-4 -mr-2 sm:-mx-4"></div>
                </div>
            </div>
            <!-- Shipping Status -->
            <div class="card col-span-1">
                <div class="card-body flex flex-col items-center justify-between">
                    <!-- Header  -->
                    <div class="flex w-full justify-between">
                        <h6>Shipping Status</h6>
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
                    <div id="shipping-status-chart" class="w-full"></div>
                    <!-- Legends -->
                    <div class="flex items-center gap-2">
                        <div class="flex cursor-default items-center gap-1.5">
                            <span class="inline-block h-[10px] w-[10px] rounded-full bg-primary-500"></span>
                            <div>
                                <p class="text-sm font-medium">70.0%</p>
                                <p class="text-xs text-slate-400">Shipped</p>
                            </div>
                        </div>
                        <div class="flex cursor-default items-center gap-1.5">
                            <span
                                class="inline-block h-[10px] w-[10px] rounded-full bg-slate-200 dark:bg-slate-600"></span>
                            <div>
                                <p class="text-sm font-medium">30.0%</p>
                                <p class="text-xs text-slate-400">Pending</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Sales Report, Order Status, Profit, Revenue, Shipping Status Section End  -->


    </div>
</x-app-layout>
