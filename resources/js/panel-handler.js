
export default class PanelHandler {

    constructor(app) {
        this.app = app
    }

    /**
     * Troca um componente em tempo de execução.
     * Os componentes nomeados para troca são:
     *   aheader      O cabeçalho
     *   afooter      O rodapé
     *   lsidebar     A barra lateral esquerda
     *   msidebar     A barra lateral esquerda em modo mobile
     *   rsidebar     A barra lateral direita
     *   pheader      O cabeçalho da página
     * 
     * @param {String} changeable 
     * @param {String} componentName
     */
    changeComponent(changeable, componentName) {
        this.app[changeable] = componentName
    }

    
    changeSidebarLeftStatus(status) {

        if (status === 'enabled') {
            this.enableSidebarLeft()
            return
        }

        this.disableSidebarLeft()
    }

    enableSidebarLeft() {
        this.app.$refs.admin.lsidebar_enable = true
        this.app.$refs.aheader.lsidebar_enable = true
    }

    disableSidebarLeft() {
        this.app.$refs.admin.lsidebar_enable = false
        this.app.$refs.aheader.lsidebar_enable = false
    }

    changeSidebarRightStatus(status) {

        if (status === 'enabled') {
            this.enableSidebarRight()
            return;
        }

        this.disableSidebarRight()
    }

    enableSidebarRight() {

        this.app.$refs.admin.rsidebar_enable = true
        this.app.$refs.pheader.rsidebar_enable = true
    }

    disableSidebarRight() {
        this.app.$refs.admin.rsidebar_enable = false
        this.app.$refs.pheader.rsidebar_enable = false
    }

    restartSidebarRight() {

        let target = document.querySelector('#apanel-sidebar-right .b-sidebar-body div')
        target.innerHTML = ''

        this.disableSidebarRight()
    }

    updateSidebarLeftAndMobile(items) {

        this.app.$refs.lsidebar.items = items
        this.app.$refs.msidebar.items = items
    }

    updateHeaderMenu(items) {
        this.app.$refs.aheader.menu_items = items
    }

    updateUserData(data) {
        this.app.$refs.aheader.user_name = data.name
        this.app.$refs.aheader.user_picture = data.picture
    }

    loadingStart() {
        this.app.$refs.admin.loading = true
    }

    loadingEnd() {
        this.app.$refs.admin.loading = false
    }
}