<template>

    <div class="list-group list-group-flush d-none d-sm-block">

        <template v-for="(item, index) in lsidebar_items">

            <b-list-group-item tag="a"
                variant="secondary"
                :key="index"
                :disabled="item.state === 'disabled' ? true : false"
                :active="item.state === 'active' ? true : false"
                @click="sidebarLeftMenu('collapse-' + index, item.href)">
                {{ item.label }}
            </b-list-group-item>

            <b-collapse :id="'collapse-' + index" v-if="item.childs">
                <div class="list-group list-group-flush">

                    <b-list-group-item tag="a"
                        variant="secondary"
                        v-for="(subitem, subindex) in item.childs"
                        :key="subindex"
                        :disabled="subitem.state === 'disabled' ? true : false"
                        :active="subitem.state === 'active' ? true : false"
                        @click="sidebarLeftSubmenu('collapse-' + index, subitem.href)">
                            {{ subitem.label }}
                    </b-list-group-item>

                </div>
            </b-collapse>

        </template>

    </div>

</template>

<script>
  export default {
    data: function () {
      return {
        lsidebar_items: this.$root.lsidebar_items,
      }
    },
    methods: {
        sidebarLeftMenu: function (id, url) {

            if (undefined !== url) {
                this.$root.loadPage(url)    
            }
            this.$root.$emit('bv::toggle::collapse', id)
        },

        sidebarLeftSubmenu: function (id, url) {

            if (undefined !== url) {
                this.$root.loadPage(url)    
            }
        }
    }
    
  }
</script>
