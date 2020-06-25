/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

const compiler = require('vue-template-compiler');

import axios from 'axios';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import PanelHandler from './panel-handler.js';
import AssetsHandler from './assets-handler.js';
import PagesHandler from './pages-handler.js';

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

// Componentes padrões
Vue.component('core-admin-full', require('./components/layout/CoreAdminFull.vue').default);
Vue.component('core-admin-header', require('./components/layout/CoreAdminHeader.vue').default);
Vue.component('core-admin-footer', require('./components/layout/CoreAdminFooter.vue').default);
Vue.component('core-sidebar-left', require('./components/layout/CoreSidebarLeft.vue').default);
Vue.component('core-sidebar-mobile', require('./components/layout/CoreSidebarMobile.vue').default);
Vue.component('core-sidebar-right', require('./components/layout/CoreSidebarRight.vue').default);
Vue.component('core-page-header', require('./components/layout/CorePageHeader.vue').default);

// Conteúdos de exemplo
Vue.component('core-page', require('./components/contents/CorePageContent.vue').default);
Vue.component('core-sidebar-right-content', require('./components/contents/CoreSidebarRightContent.vue').default);

window.app = new Vue({ 
  el: '#vue-app',
  methods: {
    loadPage: function(url){

      PagesHandler.fetchPage(this.$root, url);

      // Vue.component('core-page', require('./components/layout/CoreAdminHeader.vue').default);
      // Vue.component('core-admin-footer', require('./components/layout/CoreAdminHeader.vue').default);
      // Vue.component('core-admin-header', require('./components/layout/CoreAdminFooter.vue').default);
    }
  },
  data: {
    // componentes disponíveis
    aheader         : 'core-admin-header',
    aheader_menu    : 'core-admin-header-menu',
    afooter         : 'core-admin-footer',
    lsidebar        : 'core-sidebar-left',
    msidebar        : 'core-sidebar-mobile',
    rsidebar        : 'core-sidebar-right',
    pheader         : 'core-page-header',
    page            : 'core-page',
    rsidebar_content: 'core-sidebar-right-content',
    loading         : false,
    loading_page    : false
  }

});

PagesHandler.fetchHomePage(app);

// ==========================================================================================
// Para trocar os componentes de acordo com o tema
//
// setTimeout(function(){
//   Vue.component('core-admin-footer', require('./components/layout/CoreAdminHeader.vue').default);
//   console.log('carregou');
//   app.$forceUpdate();
// }, 2000);
//
// ==========================================================================================
