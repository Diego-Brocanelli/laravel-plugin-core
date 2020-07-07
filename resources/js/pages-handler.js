
import axios from 'axios';
import Vue from 'vue';
const compiler = require('vue-template-compiler');

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

export default class PagesHandler {

  constructor(app) {
    this.app = app;
  }

  setPageTitle(title) {
    this.app.$refs.pheader.title = title;
  }

  setBreadcrumbItems(items) {
    this.app.$refs.pheader.items = items;
  }

  fetchHomePage() {

    let app = this.app;

    axios.get('/core/meta')
      .then(function (response) {
        let home = response.data.meta.home_url;
        let version = response.data.meta.version;

        app.pages().fetchPage(home);
        console.log("-------------------------------")
        console.log("Plugin Core " + version)
        console.log("-------------------------------")

      })
      .catch(function (error) {
        app.pages().showError(error);
      })
      .then(function () {

      });
  }

  /**
   * Faz a requisição de uma página .vue e efetua a compilação do componente da página.
   * 
   * @param {String} url 
   */
  fetchPage(url) {

    let app = this.app;

    app.panel().loadingStart();

    // Normalmente o carregamento de módulos seria feito através do webpack.
    // Mas, para prover uma comunicação mais previsível com o Laravel,
    // faz-se chamadas ajax e posteriormente compila-se o componente
    axios.get(url)
      .then(function (response) {

        app.panel().restartSidebarRight()

        // Usa o 'vue-template-compiler' para 
        // interpretar do arquivo .vue e compilar os componentes
        // Isso extrai os mesmos parâmetros presentes em componentes SingleFile
        // Mais info: https://br.vuejs.org/v2/guide/components.html
        let parsed = compiler.parseComponent(response.data.vuefile)

        // ----------------------------------
        // Metadados provenientes do Laravel
        // ----------------------------------

        // Aplica os assets do tema no DOM
        app.assets().applyAppStyles(response.data.meta.styles)
        app.assets().applyAppScripts(response.data.meta.scripts)

        // Atualiza os componentes reativos do painel
        app.panel().changeSidebarLeftStatus(response.data.meta.sidebar_left_status)
        app.panel().changeSidebarRightStatus(response.data.meta.sidebar_right_status)
        app.panel().updateSidebarLeftAndMobile(response.data.meta.sidebar_left)
        app.panel().updateHeaderMenu(response.data.meta.header_menu)
        app.panel().updateUserData(response.data.meta.user_data)

        // Atualiza as informações da página atual
        app.pages().setPageTitle(response.data.meta.page_title)
        app.pages().setBreadcrumbItems(response.data.meta.breadcrumb)

        // ----------------------------------
        // Executa o script para sobrescrever os metadados
        // O templeate tem precedência em relação aos metadados
        // ----------------------------------

        if (parsed.script !== null
          && undefined !== parsed.script.content
          && parsed.script.content
        ) {

          // Remove o 'export default', pois o 'import' não será usado aqui
          let scoped = parsed.script.content.replace(/.*export.*default/, '')

          // Adiciona o script ao DOM, para dar visibilidade ao pageScoped
          app.assets().replacePageScript('page-script', 'pageScoped = ' + scoped)

        } else {

          // se o arquivo .vue não contiver scripts
          pageScoped = {}
        }

        // Adiciona o template compilado no escopo do componente dinâmico
        pageScoped.template = parsed.template.content

        // Cria o novo componente e disponibiliza sua referência na tag 'page'
        // Sempre existirá apenas uma referência para page: a página atual!
        let component = Vue.component('dynamic-component', Object.assign(pageScoped))
        app.$refs.page = component

        // Substitui o componente dinâmico atual da página
        Vue.component('core-page', component)

        // Aplica os estilos do escopo da página
        if (undefined !== parsed.styles[0]) {
          app.assets().replacePageStyle('page-style', parsed.styles[0].content)
        }

        // Pede para o Vue atualizar a árvore de componentes
        app.$forceUpdate()

        // Dispara o evento de montagem
        if (typeof pageScoped.mount === "function") { 
          pageScoped.mount()
        }

      })
      .catch(function (error) {
        app.pages().showError(error)
      })
      .then(function () {
        app.panel().loadingEnd()
      });
  }

  showError(errorData) {
    console.log(errorData)
  }
}