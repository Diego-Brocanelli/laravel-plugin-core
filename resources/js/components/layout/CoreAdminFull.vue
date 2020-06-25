<template>

    <div class="admin-wrapper">

        <b-overlay valiant="transparent" :show="admin_overlay" rounded="sm">

            <slot name="admin-header"></slot>
            
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

            <slot name="admin-footer"></slot>

            <slot name="sidebar-right"></slot>

        </b-overlay>

    </div>
  
</template>

<script>
  export default {
    data: function () {
      return {
        admin_overlay    : this.$root.admin_overlay
      }
    }
  }
</script>