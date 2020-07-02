
import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import PanelHandler from './panel-handler.js'
import PagesHandler from './pages-handler.js'
import AssetsHandler from './assets-handler.js'
import axios from 'axios';

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

// Componentes padrões
Vue.component('core-admin-full', require('./components/layout/CoreAdminFull.vue').default)
Vue.component('core-admin-header', require('./components/layout/CoreAdminHeader.vue').default)
Vue.component('core-admin-footer', require('./components/layout/CoreAdminFooter.vue').default)
Vue.component('core-sidebar-left', require('./components/layout/CoreSidebarLeft.vue').default)
Vue.component('core-sidebar-mobile', require('./components/layout/CoreSidebarMobile.vue').default)
Vue.component('core-sidebar-right', require('./components/layout/CoreSidebarRight.vue').default)
Vue.component('core-page-header', require('./components/layout/CorePageHeader.vue').default)
Vue.component('core-page', require('./components/widgets/CorePageContent.vue').default)
Vue.component('sidebar-right-content', require('./components/widgets/CoreSidebarRightContent.vue').default)

window.app = new Vue({
  el: '#vue-app',
  methods: {
    request() {
      return axios;
    },
    panel() {
      return new PanelHandler(this);
    },
    pages() {
      return new PagesHandler(this);
    },
    assets() {
      return new AssetsHandler(this);
    }
  },
  data: {
    // componentes dinâmicos substituíveis
    aheader: 'core-admin-header',
    afooter: 'core-admin-footer',
    lsidebar: 'core-sidebar-left',
    msidebar: 'core-sidebar-mobile',
    rsidebar: 'core-sidebar-right',
    pheader: 'core-page-header',
    page: 'core-page'
  }

});

app.pages().fetchHomePage()
