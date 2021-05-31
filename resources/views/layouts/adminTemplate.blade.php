<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="../admin/img/icons/icon-48x48.png" />
    <script src="{{ asset('../js/app.js') }}" defer></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css') . '?v=echo time();' }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar" data-simplebar="init">
                <div class="simplebar-wrapper" style="margin: 0px;">
                    <div class="simplebar-height-auto-observer-wrapper">
                        <div class="simplebar-height-auto-observer"></div>
                    </div>
                    <div class="simplebar-mask">
                        <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                            <div class="simplebar-content-wrapper" style="height: 100%; overflow: hidden scroll;">
                                <div class="simplebar-content" style="padding: 0px;">
                                    <a class="sidebar-brand" href="index.html">
                                        <span class="sidebar-brand-text align-middle">
                                            AdminKit
                                            <sup><small class="badge bg-primary text-uppercase">Pro</small></sup>
                                        </span>
                                        <svg class="sidebar-brand-icon align-middle" width="32px" height="32px" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter" color="#FFFFFF" style="margin-left: -3px">
                                            <path d="M12 4L20 8.00004L12 12L4 8.00004L12 4Z"></path>
                                            <path d="M20 12L12 16L4 12"></path>
                                            <path d="M20 16L12 20L4 16"></path>
                                        </svg>
                                    </a>


                                    <ul class="sidebar-nav">
                                        <li class="sidebar-header">
                                            Pages
                                        </li>
                                        <li class="sidebar-item {{ isActiveRoute('dashboard') }}">
                                            <a data-bs-target="#dashboards" data-bs-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sliders align-middle">
                                                    <line x1="4" y1="21" x2="4" y2="14"></line>
                                                    <line x1="4" y1="10" x2="4" y2="3"></line>
                                                    <line x1="12" y1="21" x2="12" y2="12"></line>
                                                    <line x1="12" y1="8" x2="12" y2="3"></line>
                                                    <line x1="20" y1="21" x2="20" y2="16"></line>
                                                    <line x1="20" y1="12" x2="20" y2="3"></line>
                                                    <line x1="1" y1="14" x2="7" y2="14"></line>
                                                    <line x1="9" y1="8" x2="15" y2="8"></line>
                                                    <line x1="17" y1="16" x2="23" y2="16"></line>
                                                </svg> <span class="align-middle">Dashboards</span>
                                            </a>
                                            <ul id="dashboards" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar" style="">
                                                <li class="sidebar-item active"><a class="sidebar-link" href="index.html">Analytics</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="dashboard-ecommerce.html">E-Commerce <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="dashboard-crypto.html">Crypto <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                            </ul>
                                        </li>

                                        <li class="sidebar-item">
                                            <a data-bs-target="#pages" data-bs-toggle="collapse" class="sidebar-link collapsed">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layout align-middle">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                    <line x1="3" y1="9" x2="21" y2="9"></line>
                                                    <line x1="9" y1="21" x2="9" y2="9"></line>
                                                </svg> <span class="align-middle">Pages</span>
                                            </a>
                                            <ul id="pages" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
                                                <li class="sidebar-item"><a class="sidebar-link" href="pages-settings.html">Settings</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="pages-projects.html">Projects <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="pages-clients.html">Clients <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="pages-pricing.html">Pricing <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="pages-chat.html">Chat <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="pages-blank.html">Blank Page</a></li>
                                            </ul>
                                        </li>

                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="pages-profile.html">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user align-middle">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg> <span class="align-middle">Profile</span>
                                            </a>
                                        </li>

                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="pages-invoice.html">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card align-middle">
                                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                                </svg> <span class="align-middle">Invoice</span>
                                            </a>
                                        </li>

                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="pages-tasks.html">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list align-middle">
                                                    <line x1="8" y1="6" x2="21" y2="6"></line>
                                                    <line x1="8" y1="12" x2="21" y2="12"></line>
                                                    <line x1="8" y1="18" x2="21" y2="18"></line>
                                                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                                                </svg> <span class="align-middle">Tasks</span>
                                                <span class="sidebar-badge badge bg-primary">Pro</span>
                                            </a>
                                        </li>

                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="calendar.html">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar align-middle">
                                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                                </svg> <span class="align-middle">Calendar</span>
                                                <span class="sidebar-badge badge bg-primary">Pro</span>
                                            </a>
                                        </li>

                                        <li class="sidebar-item">
                                            <a href="#auth" data-bs-toggle="collapse" class="sidebar-link collapsed">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users align-middle">
                                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="9" cy="7" r="4"></circle>
                                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                                </svg> <span class="align-middle">Auth</span>
                                            </a>
                                            <ul id="auth" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
                                                <li class="sidebar-item"><a class="sidebar-link" href="pages-sign-in.html">Sign In</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="pages-sign-up.html">Sign Up</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="pages-reset-password.html">Reset Password <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="pages-404.html">404 Page <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="pages-500.html">500 Page <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                            </ul>
                                        </li>

                                        <li class="sidebar-header">
                                            Components
                                        </li>
                                        <li class="sidebar-item">
                                            <a data-bs-target="#ui" data-bs-toggle="collapse" class="sidebar-link collapsed">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase align-middle">
                                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                                </svg> <span class="align-middle">UI Elements</span>
                                            </a>
                                            <ul id="ui" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
                                                <li class="sidebar-item"><a class="sidebar-link" href="ui-alerts.html">Alerts</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="ui-buttons.html">Buttons</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="ui-cards.html">Cards</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="ui-general.html">General</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="ui-grid.html">Grid</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="ui-modals.html">Modals</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="ui-offcanvas.html">Offcanvas <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="ui-tabs.html">Tabs <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="ui-typography.html">Typography</a></li>
                                            </ul>
                                        </li>
                                        <li class="sidebar-item">
                                            <a data-bs-target="#forms" data-bs-toggle="collapse" class="sidebar-link collapsed">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle align-middle">
                                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                                </svg> <span class="align-middle">Forms</span>
                                            </a>
                                            <ul id="forms" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
                                                <li class="sidebar-item"><a class="sidebar-link" href="forms-basic-inputs.html">Basic Inputs</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="forms-layouts.html">Form Layouts <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="forms-input-groups.html">Input Groups <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                            </ul>
                                        </li>
                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="tables-bootstrap.html">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list align-middle">
                                                    <line x1="8" y1="6" x2="21" y2="6"></line>
                                                    <line x1="8" y1="12" x2="21" y2="12"></line>
                                                    <line x1="8" y1="18" x2="21" y2="18"></line>
                                                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                                                </svg> <span class="align-middle">Tables</span>
                                            </a>
                                        </li>

                                        <li class="sidebar-header">
                                            Plugins &amp; Addons
                                        </li>
                                        <li class="sidebar-item">
                                            <a data-bs-target="#form-plugins" data-bs-toggle="collapse" class="sidebar-link collapsed">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square align-middle">
                                                    <polyline points="9 11 12 14 22 4"></polyline>
                                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                                </svg> <span class="align-middle">Form Plugins</span>
                                            </a>
                                            <ul id="form-plugins" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
                                                <li class="sidebar-item"><a class="sidebar-link" href="forms-advanced-inputs.html">Advanced Inputs <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="forms-editors.html">Editors <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="forms-validation.html">Validation <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                            </ul>
                                        </li>
                                        <li class="sidebar-item">
                                            <a data-bs-target="#datatables" data-bs-toggle="collapse" class="sidebar-link collapsed">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list align-middle">
                                                    <line x1="8" y1="6" x2="21" y2="6"></line>
                                                    <line x1="8" y1="12" x2="21" y2="12"></line>
                                                    <line x1="8" y1="18" x2="21" y2="18"></line>
                                                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                                                </svg> <span class="align-middle">DataTables</span>
                                            </a>
                                            <ul id="datatables" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
                                                <li class="sidebar-item"><a class="sidebar-link" href="tables-datatables-responsive.html">Responsive Table <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="tables-datatables-buttons.html">Table with Buttons <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="tables-datatables-column-search.html">Column Search <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="tables-datatables-fixed-header.html">Fixed Header <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="tables-datatables-multi.html">Multi Selection <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="tables-datatables-ajax.html">Ajax Sourced Data <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                            </ul>
                                        </li>
                                        <li class="sidebar-item">
                                            <a data-bs-target="#charts" data-bs-toggle="collapse" class="sidebar-link collapsed">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2 align-middle">
                                                    <line x1="18" y1="20" x2="18" y2="10"></line>
                                                    <line x1="12" y1="20" x2="12" y2="4"></line>
                                                    <line x1="6" y1="20" x2="6" y2="14"></line>
                                                </svg> <span class="align-middle">Charts</span>
                                            </a>
                                            <ul id="charts" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
                                                <li class="sidebar-item"><a class="sidebar-link" href="charts-chartjs.html">Chart.js</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="charts-apexcharts.html">ApexCharts <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                            </ul>
                                        </li>
                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="notifications.html">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell align-middle">
                                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                                </svg> <span class="align-middle">Notifications</span>
                                                <span class="sidebar-badge badge bg-primary">Pro</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a data-bs-target="#maps" data-bs-toggle="collapse" class="sidebar-link collapsed">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map align-middle">
                                                    <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                                                    <line x1="8" y1="2" x2="8" y2="18"></line>
                                                    <line x1="16" y1="6" x2="16" y2="22"></line>
                                                </svg> <span class="align-middle">Maps</span>
                                            </a>
                                            <ul id="maps" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
                                                <li class="sidebar-item"><a class="sidebar-link" href="maps-google.html">Google Maps</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link" href="maps-vector.html">Vector Maps <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                            </ul>
                                        </li>

                                        <li class="sidebar-item">
                                            <a data-bs-target="#multi" data-bs-toggle="collapse" class="sidebar-link collapsed">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-corner-right-down align-middle">
                                                    <polyline points="10 15 15 20 20 15"></polyline>
                                                    <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                                </svg> <span class="align-middle">Multi Level</span>
                                            </a>
                                            <ul id="multi" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                                                <li class="sidebar-item">
                                                    <a data-bs-target="#multi-2" data-bs-toggle="collapse" class="sidebar-link collapsed">Two Levels</a>
                                                    <ul id="multi-2" class="sidebar-dropdown list-unstyled collapse">
                                                        <li class="sidebar-item">
                                                            <a class="sidebar-link" href="#">Item 1</a>
                                                            <a class="sidebar-link" href="#">Item 2</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="sidebar-item">
                                                    <a data-bs-target="#multi-3" data-bs-toggle="collapse" class="sidebar-link collapsed">Three Levels</a>
                                                    <ul id="multi-3" class="sidebar-dropdown list-unstyled collapse">
                                                        <li class="sidebar-item">
                                                            <a data-bs-target="#multi-3-1" data-bs-toggle="collapse" class="sidebar-link collapsed">Item 1</a>
                                                            <ul id="multi-3-1" class="sidebar-dropdown list-unstyled collapse">
                                                                <li class="sidebar-item">
                                                                    <a class="sidebar-link" href="#">Item 1</a>
                                                                    <a class="sidebar-link" href="#">Item 2</a>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                        <li class="sidebar-item">
                                                            <a class="sidebar-link" href="#">Item 2</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>

                                    <div class="sidebar-cta">
                                        <div class="sidebar-cta-content">
                                            <strong class="d-inline-block mb-2">Weekly Sales Report</strong>
                                            <div class="mb-3 text-sm">
                                                Your weekly sales report is ready for download!
                                            </div>

                                            <div class="d-grid">
                                                <a href="https://adminkit.io/" class="btn btn-outline-primary" target="_blank">Download</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="simplebar-placeholder" style="width: auto; height: 1199px;"></div>
                </div>
                <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                    <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                </div>
                <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                    <div class="simplebar-scrollbar" style="height: 434px; transform: translate3d(0px, 287px, 0px); display: block;"></div>
                </div>
            </div>
        </nav>

        <div class="main">
            <nav class="navbar navbar-expand navbar-light navbar-bg">
                <a class="sidebar-toggle d-flex">
                    <i class="hamburger align-self-center"></i>
                </a>

                <form class="d-none d-sm-inline-block">
                    <div class="input-group input-group-navbar">
                        <input type="text" class="form-control" placeholder="Search…" aria-label="Search">
                        <button class="btn" type="button">
                            <i class="align-middle" data-feather="search"></i>
                        </button>
                    </div>
                </form>

                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align">
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-toggle="dropdown">
                                <div class="position-relative">
                                    <i class="align-middle" data-feather="bell"></i>
                                    <span class="indicator">4</span>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right py-0" aria-labelledby="alertsDropdown">
                                <div class="dropdown-menu-header">
                                    4 New Notifications
                                </div>
                                <div class="list-group">
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-danger" data-feather="alert-circle"></i>
                                            </div>
                                            <div class="col-10">
                                                <div class="text-dark">Update completed</div>
                                                <div class="text-muted small mt-1">Restart server 12 to complete the update.</div>
                                                <div class="text-muted small mt-1">30m ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-warning" data-feather="bell"></i>
                                            </div>
                                            <div class="col-10">
                                                <div class="text-dark">Lorem ipsum</div>
                                                <div class="text-muted small mt-1">Aliquam ex eros, imperdiet vulputate hendrerit et.</div>
                                                <div class="text-muted small mt-1">2h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-primary" data-feather="home"></i>
                                            </div>
                                            <div class="col-10">
                                                <div class="text-dark">Login from 192.186.1.8</div>
                                                <div class="text-muted small mt-1">5h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-success" data-feather="user-plus"></i>
                                            </div>
                                            <div class="col-10">
                                                <div class="text-dark">New connection</div>
                                                <div class="text-muted small mt-1">Christina accepted your request.</div>
                                                <div class="text-muted small mt-1">14h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dropdown-menu-footer">
                                    <a href="#" class="text-muted">Show all notifications</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle" href="#" id="messagesDropdown" data-toggle="dropdown">
                                <div class="position-relative">
                                    <i class="align-middle" data-feather="message-square"></i>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right py-0" aria-labelledby="messagesDropdown">
                                <div class="dropdown-menu-header">
                                    <div class="position-relative">
                                        4 New Messages
                                    </div>
                                </div>
                                <div class="list-group">
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <img src="../admin/img/avatars/avatar-5.jpg" class="avatar img-fluid rounded-circle" alt="Vanessa Tucker">
                                            </div>
                                            <div class="col-10 pl-2">
                                                <div class="text-dark">Vanessa Tucker</div>
                                                <div class="text-muted small mt-1">Nam pretium turpis et arcu. Duis arcu tortor.</div>
                                                <div class="text-muted small mt-1">15m ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <img src="../admin/img/avatars/avatar-2.jpg" class="avatar img-fluid rounded-circle" alt="William Harris">
                                            </div>
                                            <div class="col-10 pl-2">
                                                <div class="text-dark">William Harris</div>
                                                <div class="text-muted small mt-1">Curabitur ligula sapien euismod vitae.</div>
                                                <div class="text-muted small mt-1">2h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <img src="../admin/img/avatars/avatar-4.jpg" class="avatar img-fluid rounded-circle" alt="Christina Mason">
                                            </div>
                                            <div class="col-10 pl-2">
                                                <div class="text-dark">Christina Mason</div>
                                                <div class="text-muted small mt-1">Pellentesque auctor neque nec urna.</div>
                                                <div class="text-muted small mt-1">4h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <img src="../admin/img/avatars/avatar-3.jpg" class="avatar img-fluid rounded-circle" alt="Sharon Lessman">
                                            </div>
                                            <div class="col-10 pl-2">
                                                <div class="text-dark">Sharon Lessman</div>
                                                <div class="text-muted small mt-1">Aenean tellus metus, bibendum sed, posuere ac, mattis non.</div>
                                                <div class="text-muted small mt-1">5h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dropdown-menu-footer">
                                    <a href="#" class="text-muted">Show all messages</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-toggle="dropdown">
                                <i class="align-middle" data-feather="settings"></i>
                            </a>

                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-toggle="dropdown">
                                <img src="../admin/img/avatars/avatar.jpg" class="avatar img-fluid rounded mr-1" alt="Charles Hall" /> <span class="text-dark">Charles Hall</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="pages-profile.html"><i class="align-middle mr-1" data-feather="user"></i> Profile</a>
                                <a class="dropdown-item" href="#"><i class="align-middle mr-1" data-feather="pie-chart"></i> Analytics</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="pages-settings.html"><i class="align-middle mr-1" data-feather="settings"></i> Settings & Privacy</a>
                                <a class="dropdown-item" href="#"><i class="align-middle mr-1" data-feather="help-circle"></i> Help Center</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('member.logout') }}">Log out</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="content">
                @yield('content')
            </main>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <p class="mb-0">
                                <a class="text-muted" href="#" target="_blank"><strong>Consumptive Material Management Website</strong></a>
                            </p>
                        </div>
                        <div class="col-6 text-end">
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <a class="text-muted" href="#" target="_blank">Support</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-muted" href="#" target="_blank">Help Center</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- vue component example -->
    <!-- <div id="vue-app">
        <example-component></example-component>
    </div> -->
    <script src="{{ asset('/admin/js/app.js') }}"></script>
</body>

</html>
