<template>
  <div>
    <template v-for="(item, index) in items">

      <b-list-group-item tag="a"
        v-if="item.children"
        :key="index"
        :disabled="item.status === 'disabled' ? true : false"
        :active="item.status === 'active' ? true : false"
        @click="msidebarLeftMenu('mcollapse-' + index, item.url, item.children !== undefined)">
          <b-icon :icon="item.icon" v-if="item.icon"></b-icon>
          {{ item.label }}
          <b-icon icon="chevron-down" class="float-right" v-if="item.children"></b-icon>
      </b-list-group-item>

      <b-list-group-item tag="a"
        v-else
        :key="index"
        :disabled="item.status === 'disabled' ? true : false"
        :active="item.status === 'active' ? true : false"
        @click="msidebarLeftMenu('mcollapse-' + index, item.url, item.children !== undefined)"
        v-b-toggle.sidebar-mobile>
          <b-icon :icon="item.icon" v-if="item.icon"></b-icon>
          {{ item.label }}
          <b-icon icon="chevron-down" class="float-right" v-if="item.children"></b-icon>
      </b-list-group-item>

      <b-collapse :id="'mcollapse-' + index" v-if="item.children">
        <div class="list-group list-group-flush">
          <b-list-group-item tag="a"
            variant="secondary"
            v-for="(subitem, subindex) in item.children"
            :key="subindex"
            :disabled="subitem.status === 'disabled' ? true : false"
            :active="subitem.status === 'active' ? true : false"
            @click="msidebarLeftSubmenu('mcollapse-' + index, subitem.url)"
            v-b-toggle.sidebar-mobile>
            <b-icon :icon="subitem.icon" v-if="subitem.icon"></b-icon>
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
        items : [],
      }
    },
    methods: {
        msidebarLeftMenu: function (id, url, hasChildren) {

            if (undefined !== url && hasChildren === false) {
                this.$root.pages().fetchPage(url)
                return
            }

            this.$root.$emit('bv::toggle::collapse', id)
        },

        msidebarLeftSubmenu: function (id, url) {

            if (undefined !== url) {
                this.$root.pages().fetchPage(url)
            }
        }
    }
  }
</script>