<template>

   <header>

        <h2 class="h3 p-3 m-0 text-secondary">
        {{ title }}
        <b-button v-b-toggle.sidebar-right class="float-right">Toggle Sidebar</b-button>
        </h2>

        <b-breadcrumb :items="breadcrumb_items_fixed"></b-breadcrumb>
        
    </header>
  
</template>

<script>
  export default {
    data: function () {
      return {
        title      : this.$root.page_title,
        breadcrumb_items: this.$root.breadcrumb_items
      }
    },
    computed: {
      breadcrumb_items_fixed: function () {
        let fixed = [];
        this.$root.breadcrumb_items.forEach(function(item, index, array){
          fixed.push({
            text: item.label,
            active: item.state === 'active' ? true : false,
            href: item.href
          });

        });
        return fixed;
      }
    },
    methods: {

    }
  }
</script>