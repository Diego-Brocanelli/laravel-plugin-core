<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="/plugins/core/css/app.css?v={{ date('dmHis') }}">

</head>

<body class="bg-dark">

    <div id="vue-app">

        <component ref="admin" is="core-admin-full">

            <component ref="aheader" slot="admin-header" :is="aheader"></component>

            <component ref="lsidebar" slot="sidebar-left" :is="lsidebar"></component>

            <component ref="msidebar" slot="sidebar-mobile" :is="msidebar"></component>

            <component ref="rsidebar" slot="sidebar-right" :is="rsidebar"></component>

            <main slot="admin-page">

                <component ref="pheader" :is="pheader"></component>

                <section class="container p-3">
                    <div class="page-wrapper">
                        <component ref="page_content" :is="page"></component>
                    </div>
                </section>

            </main>

            <component slot="admin-footer" :is="afooter"></component>

        </component>

    </div>

    <script type="text/javascript" src="/plugins/core/js/app.js?v={{ date('dmHis') }}"></script>

</body>

</html>