<template>

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

      <component v-for="(item, index) in header_menu_items" 
        :is="item.type === 'entry' ? 'b-dropdown-item' : 'b-dropdown-divider'" 
        :key="index"
        :disabled="item.state === 'disabled' ? true : false"
        :active="item.state === 'active' ? true : false"
        :href="item.href"
        >
        {{ item.label }}
      </component>
      
    </b-dropdown>
  </div>
  
</template>

<script>
  export default {
    data: function () {
      return {
        user_name        : this.$root.user_name,
        user_picture     : this.$root.user_picture,
        header_menu_items: this.$root.header_menu_items,
      }
    }
  }
</script>
