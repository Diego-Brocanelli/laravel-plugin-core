
import Vue from 'vue';
import AssetsHandler from './assets-handler.js';

// lsidebar

export default class PanelHandler {
  
    static updateSidebarLeft(app, items)
    {
        app.$refs.lsidebar.items = items;
        app.$refs.msidebar.items = items;
    }

    static updateSidebarRight(app, content)
    {
        // app.$refs.pheader.breadcrumb = items;
    }

    static updateHeaderMenu(app, items)
    {
        app.$refs.aheader.menu_items = items;
    }

    static updateUserData(app, data)
    {
        app.$refs.aheader.user_name = data.name;
        app.$refs.aheader.user_picture = data.picture;
    }

    static loadingStart(app)
    {
        app.$refs.admin.loading = true;
    }

    static loadingEnd(app)
    {
        app.$refs.admin.loading = false;
    }
}