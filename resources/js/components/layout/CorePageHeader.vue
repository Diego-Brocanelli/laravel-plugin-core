<template>

   <header>

        <h2 class="h3 p-3 m-0 text-secondary">
        {{ title }}
        <b-icon :icon="sidebar_icon" v-b-toggle.apanel-sidebar-right class="float-right" v-if="rsidebar_enable"></b-icon>
        </h2>

        <div class="d-none d-sm-block">
          <b-breadcrumb>

            <template v-for="(item, index) in items">

              <b-breadcrumb-item @click="breadcrumbUrl(item.url)" :key="index">
                <b-icon v-if="item.icon" :icon="item.icon" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                {{ item.label  }}
              </b-breadcrumb-item>

            </template>

          </b-breadcrumb>
        </div>
        
    </header>
  
</template>

<script>
  export default {
    data: function () {
      return {
        title     : 'Página',
        sidebar_icon: 'three-dots-vertical',
        items: [],
        rsidebar_enable: false
      }
    },
    methods: {
      breadcrumbUrl: function (url) {

        if (undefined !== url) {
          this.$root.loadPage(url)    
        }
      }
    },
  }
</script>