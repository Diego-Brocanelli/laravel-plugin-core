<template>

    <div class="bg-primary text-light sticky-top">
        <div class="container-fluid p-0">
            <div class="d-flex align-items-stretch">

                <div v-if="lsidebar_enable" 
                  id="apanel-header-toogle-sidebar"
                  class="btn-module-menu px-3 py-2 d-block d-sm-none btn" 
                  v-b-toggle.apanel-sidebar-mobile>
					        <b-icon :icon="mobile_icon" class="float-right align-self-center"></b-icon>
                </div>

                <div v-else 
                  id="apanel-header-toogle-sidebar"
                  class="btn-module-menu px-3 py-2 btn" 
                  v-b-toggle.apanel-sidebar-mobile>
					        <b-icon :icon="mobile_icon" class="float-right align-self-center"></b-icon>
                </div>
                
                <div class="d-flex align-items-center">
                    <h1 class="h4 m-0 ml-3">
                        Vuejs
                        <small>by <a href="https://github.com/bueno-networks" class="text-light">Bueno Networks</a></small>
                    </h1>
                </div>

                <div class="ml-auto">
                    <b-dropdown id="apanel-header-main-dropdown" 
                    variant="primary"
                    no-caret
                    right>

                      <template v-slot:button-content>
                          <div class="px-3 py-2">
                          <span class="d-none d-sm-inline mr-2">{{ user_name }}</span>
                          <b-img :src="user_picture" rounded="circle" class="h-100 border shadow" alt="Responsive image"></b-img> 
                          </div>
                      </template>

                      <core-admin-header-menu v-for="(item, index) in menu_items" 
                          :is="item.type === 'item' ? 'b-dropdown-item' : 'b-dropdown-divider'" 
                          :key="index"
                          :disabled="item.status === 'disabled' ? true : false"
                          :active="item.status === 'active' ? true : false"
                          @click="menuUrl(item.url)"
                          >
                          <b-icon v-if="item.icon" :icon="item.icon" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                          {{ item.label }}
                      </core-admin-header-menu>
                    
                    </b-dropdown>
                </div>
                
            </div>
            
        </div>

    </div>
  
</template>

<script>
  export default {
    data: function () {
      return {
		    mobile_icon: 'list',
		    user_name   : 'Cacilda',
		    user_picture: 'http://lorempixel.com/25/25/people/9/',
		    menu_items  : [],
        lsidebar_enable: true
      }
    },
	methods: {
      menuUrl: function (url) {

        if (undefined !== url) {
          this.$root.pages().fetchPage(url)
        }
      }
    },
  }
</script>
