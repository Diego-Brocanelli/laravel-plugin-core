/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
const compiler = require('vue-template-compiler');

import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import AssetsHandler from './assets-handler.js';

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

// Componentes padrões
Vue.component('core-admin-full', require('./components/layout/CoreAdminFull.vue').default);
Vue.component('core-admin-header', require('./components/layout/CoreAdminHeader.vue').default);
Vue.component('core-admin-header-menu', require('./components/layout/CoreAdminHeaderMenu.vue').default);
Vue.component('core-admin-footer', require('./components/layout/CoreAdminFooter.vue').default);
Vue.component('core-sidebar-left', require('./components/layout/CoreSidebarLeft.vue').default);
Vue.component('core-sidebar-right', require('./components/layout/CoreSidebarRight.vue').default);
Vue.component('core-page-header', require('./components/layout/CorePageHeader.vue').default);

// Conteúdos de exemplo
Vue.component('core-page', require('./components/contents/CorePageContent.vue').default);
Vue.component('core-sidebar-right-content', require('./components/contents/CoreSidebarRightContent.vue').default);
Vue.component('core-example', require('./components/contents/CoreExample.vue').default);

window.app = new Vue({ 
  el: '#vue-app',
  methods: {
    loadPage: function(url){

      var root = this.$root;

      root.page_overlay = true;

      axios.get(url)
        .then(function (response) {

          console.log(response);

          // Interpreta do arquivo .vue
          let parsed = compiler.parseComponent(response.data.vuefile);
          let component = Vue.component('dynamic-component', Object.assign({
            template: parsed.template.content
          }));

          // Adiciona os assets corrrespondentes ao DOM
          AssetsHandler.replacePageScript('page-script', parsed.script.content);
          if (parsed.styles[0]) {
            AssetsHandler.replacePageStyle('page-style', parsed.styles[0].content);
          }
          
          // Substitui o componente da página
          Vue.component('core-page', component);
          root.$forceUpdate();

        })
        .catch(function (error) {
          console.log(error);
        })
        .then(function () {
            root.page_overlay = false;
        }); 

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
    rsidebar        : 'core-sidebar-right',
    pheader         : 'core-page-header',
    page            : 'core-page',
    rsidebar_content: 'core-sidebar-right-content',
    
    // informações consumidas
    admin_overlay    : false,
    page_overlay     : false,
    user_name        : 'Clair Redfield',
    user_picture     : 'http://lorempixel.com/25/25/people/9/',
    header_menu_items: [
      {
        type: 'entry',
        state: 'common',
        label: 'First Action',
        href: 'acao1',
      },
      {
        type: 'entry',
        state: 'common',
        label: 'Second Action',
        href: 'acao2',
      },
      {
        type: 'entry',
        state: 'common',
        label: 'Third Action',
        href: 'acao3',
      },
      {
        type: 'divider'
      },
      {
        type: 'entry',
        state: 'active',
        label: 'Active Action',
        href: '#',
      },
      {
        type: 'entry',
        state: 'disabled',
        label: 'Disabled Action',
        href: '#',
      }
    ],

    lsidebar_items: [
      {
        type: 'entry',
        state: 'common',
        label: 'First Action',
        href: '/page',
      },
      {
        type: 'entry',
        state: 'common',
        label: 'Second Action',
        // href: '',
        childs: [
          {
            type: 'entry',
            state: 'common',
            label: 'First Child',
            href: 'acao8',
          },
          {
            type: 'entry',
            state: 'common',
            label: 'Second Child',
            href: 'acao9',
          },  
        ]
      },
      {
        type: 'entry',
        state: 'common',
        label: 'Third Action',
        href: 'acao3',
      },
      
      {
        type: 'entry',
        state: 'active',
        label: 'Active Action',
        href: '#',
      },
      {
        type: 'entry',
        state: 'disabled',
        label: 'Disabled Action',
        href: '#',
      }
    ],

    page_title: 'Página sem nome',
    breadcrumb_items: [
      {
        label: 'Home',
        state: 'common',
        href: 'acao1',
      },
      {
        label: 'Pages',
        state: 'common',
        href: 'acao2',
      },
      {
        label: 'Page',
        state: 'active',
        href: '',
      },
    ]
  }
});

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
