<template>

    <div id="sidebar-right-content">
      <slot></slot> 
    </div>

</template>

<script>
  import Vue from 'vue';

  export default {
    data: function () {
      return {
		    loading: false,
        lsidebar_enable: true,
        rsidebar_enable: false
      }
    },
    mounted() {

      this.$root.panel().enableSidebarRight()

      let title = this.$el.getAttribute("title")
      if (title !== null) {
        let titleElement = document.querySelector('#apanel-sidebar-right .b-sidebar-header span')
        titleElement.textContent = title
      }

      let bodyElement = document.querySelector('#apanel-sidebar-right .b-sidebar-body div')
      bodyElement.innerHTML = ''
      bodyElement.appendChild(this.$el)
    }
  }
</script>

