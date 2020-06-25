<template>

    <header class="bg-info text-light sticky-top">
        <div class="container-fluid p-0">
            <div class="d-flex align-items-stretch">

                <div class="btn-module-menu px-4 py-3 d-block d-sm-none btn btn-info rounded-0 border-right" v-b-toggle.sidebar-mobile>
					        <b-icon :icon="mobile_icon" font-scale="2" class="float-right align-self-center"></b-icon>
                </div>
                
                <div class="d-flex align-items-center">
                    <h1 class="h4 m-0 ml-3">
                        Vuejs
                        <small>by <a href="https://github.com/bueno-networks" class="text-light">Bueno Networks</a></small>
                    </h1>
                </div>

                <div class="ml-auto">
                    <b-dropdown id="dropdown-1" 
                    class="rounded-0 border-left"
                    variant="info"
                    no-caret
                    right>

                      <template v-slot:button-content>
                          <div class="px-3 py-2">
                          <span class="d-none d-sm-inline mr-2">{{ user_name }}</span>
                          <b-img :src="user_picture" rounded="circle" class="h-100 border shadow" alt="Responsive image"></b-img> 
                          </div>
                      </template>

                      <component v-for="(item, index) in menu_items" 
                          :is="item.type === 'item' ? 'b-dropdown-item' : 'b-dropdown-divider'" 
                          :key="index"
                          :disabled="item.status === 'disabled' ? true : false"
                          :active="item.status === 'active' ? true : false"
                          @click="menuUrl(item.url)"
                          >
                          <b-icon v-if="item.icon" :icon="item.icon" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                          {{ item.label }}
                      </component>
                    
                    </b-dropdown>
                </div>
                
            </div>
            
        </div>

    </header>
  
</template>

<script>
  export default {
    data: function () {
      return {
		    mobile_icon: 'list',
		    user_name   : 'Cacilda',
		    user_picture: 'http://lorempixel.com/25/25/people/9/',
		    menu_items  : [],
      }
    },
	methods: {
      menuUrl: function (url) {

        if (undefined !== url) {
          this.$root.loadPage(url)    
        }
      }
    },
  }
</script>
