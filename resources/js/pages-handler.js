
import axios from 'axios';
import Vue from 'vue';
import PanelHandler from './panel-handler.js';
import AssetsHandler from './assets-handler.js';
const compiler = require('vue-template-compiler');

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

export default class PagesHandler {
  
    static setPageTitle(app, title)
    {
      app.$refs.pheader.title = title;
    }

    static setBreadcrumbItems(app, items)
    {
      app.$refs.pheader.items = items;
    }

    static fetchHomePage(app)
    {
        axios.get('/core/meta')
          .then(function (response) {
            let home = response.data.meta.home_url;
            PagesHandler.fetchPage(app,home);
          })
          .catch(function (error) {
            PagesHandler.showError(app, error);
          })
          .then(function () {
            
          }); 
    }

    static fetchPage(app, url)
    {
        // app.page_overlay = true;
        PanelHandler.loadingStart(app);

        axios.get(url)
          .then(function (response) {
  
            PanelHandler.loadingEnd(app);

            // Interpreta do arquivo .vue
            let parsed = compiler.parseComponent(response.data.vuefile);
            let component = Vue.component('dynamic-component', Object.assign({
              template: parsed.template.content
            }));
  
            // Adiciona os assets do tema 
            AssetsHandler.applyAppStyles(response.data.meta.styles);
            AssetsHandler.applyAppScripts(response.data.meta.scripts);
    
            // Adiciona os assets corrrespondentes ao DOM
            AssetsHandler.replacePageScript('page-script', parsed.script.content);
            if (parsed.styles[0]) {
              AssetsHandler.replacePageStyle('page-style', parsed.styles[0].content);
            }

            PanelHandler.updateSidebarLeft(app, response.data.meta.sidebar_left);
            PanelHandler.updateHeaderMenu(app, response.data.meta.header_menu);
            PanelHandler.updateUserData(app, response.data.meta.user_data);

            PagesHandler.setPageTitle(app, response.data.meta.page_title);
            PagesHandler.setBreadcrumbItems(app, response.data.meta.breadcrumb);
            
            // Substitui o componente da página
            Vue.component('core-page', component);
            app.$forceUpdate();

          })
          .catch(function (error) {

            PanelHandler.loadingEnd(app);
            PagesHandler.showError(app, error);

          })
          .then(function () {
            //app.page_overlay = false;
          }); 
    }

    static showError(app, errorData)
    {
      console.log(errorData);
    }
}