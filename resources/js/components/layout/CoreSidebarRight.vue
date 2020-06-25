<template>
  <div>
    <b-sidebar id="sidebar-right" title="Sidebar" right shadow>
      <component :is="rsidebar_content"></component>
    </b-sidebar>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        rsidebar_content : this.$root.rsidebar_content,
      }
    }
  }
</script>