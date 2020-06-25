<template>

    <div class="admin-wrapper">

          <slot name="admin-header"></slot>
          
          <b-overlay valiant="transparent" :show="loading" rounded="sm">

            <div class="container-fluid bg-light">

                <div class="row">

                    <div class="align-self-stretch col-sm p-0 bg-secondary" style="flex: 0 0 260px;">
                        <slot name="sidebar-left"></slot>
                    </div>

                    <div class="col-sm p-0">
                        <slot name="admin-page"></slot>
                    </div>

                </div>

            </div>

          </b-overlay>

          <slot name="admin-footer"></slot>

          <slot name="sidebar-right"></slot>
          <slot name="sidebar-mobile"></slot>

    </div>
  
</template>

<script>
  export default {
    data: function () {
      return {
		    loading: false
      }
    }
  }
</script>