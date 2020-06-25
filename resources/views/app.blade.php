<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>{{ config('app.name') }}</title>

        <link rel="stylesheet" href="/plugins/core/css/app.css?v={{ date('dmHis')}}">

    </head>

    <body class="bg-dark">

        <div id="vue-app">

            <b-overlay valiant="transparent" :show="admin_overlay" rounded="sm">

                <component is="core-admin-full">

                    <!-- Slots de layout -->
                    <component slot="admin-header" :is="aheader"></component>

                    <component slot="sidebar-left" :is="lsidebar"></component>

                    <component slot="sidebar-right" :is="rsidebar"></component>

                    <main slot="admin-page">

                        <component ref="page_header" :is="pheader"></component>

                        <section class="p-3">
                            <div class="page-wrapper">
                                <b-overlay valiant="transparent" :show="page_overlay" rounded="sm">
                                
                                <component ref="page_content" :is="page"></component>

                                </b-overlay>
                            </div>
                        </section>

                    </main>

                    <component slot="admin-footer" :is="afooter"></component>

                </component>
            </b-overlay>
        </div>

        <script type="text/javascript" src="/plugins/core/js/components.js"></script>
        <script type="text/javascript" src="/plugins/core/js/app.js?v={{ date('dmHis')}}"></script>
    </body>
    
</html>