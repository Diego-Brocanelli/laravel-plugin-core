<template>

  <div id="apanel-wrapper">

    <header id="apanel-header">
      <slot name="admin-header"></slot>
    </header>
        
    <b-overlay valiant="transparent" :show="loading" rounded="sm">

      <div class="container-fluid bg-light">
        <div class="row">
          <div id="apanel-sidebar-left" class="align-self-stretch col-sm p-0 bg-secondary" 
               style="flex: 0 0 260px;" v-show="lsidebar_enable">
            <div class="list-group list-group-flush d-none d-sm-block">
              <slot name="sidebar-left"></slot>
            </div>
          </div>

          <div id="apanel-page" class="col-sm p-0">
            <slot name="admin-page"></slot>
          </div>
        </div>
      </div>

    </b-overlay>

    <footer id="apanel-footer">
      <slot name="admin-footer"></slot>
    </footer>

    <b-sidebar id="apanel-sidebar-right" class="apanel-sidebar-right" right shadow v-show="rsidebar_enable">
      <slot name="sidebar-right"></slot>
    </b-sidebar>

    <b-sidebar id="apanel-sidebar-mobile" left shadow>
      <div class="list-group list-group-flush">
        <slot name="sidebar-mobile"></slot>
      </div>
    </b-sidebar>

  </div>
  
</template>

<script>
  export default {
    data: function () {
      return {
		    loading: false,
        lsidebar_enable: true,
        rsidebar_enable: false
      }
    }
  }
</script>