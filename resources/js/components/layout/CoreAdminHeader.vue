<template>

	<header class="bg-info text-light">
		<div class="container-fluid p-0">
			<div class="d-flex align-items-stretch">

				<div id="js-mobile-menu" 
					class="btn-module-menu px-4 py-3 d-block d-sm-none btn btn-info rounded-0 border-right">
					<i class="fas fa-bars align-self-center" aria-hidden="true"></i>
				</div>
				
				<div class="d-flex align-items-center">
					<h1 class="h4 m-0 ml-3">
						Bootstrap 4
						<small>by <a href="https://github.com/bueno-networks" class="text-light">Bueno Networks</a></small>
					</h1>
				</div>

				<component :is="aheader_menu"></component>
				
			</div>
			
		</div>
	</header>
  
</template>

<script>
  export default {
    data: function () {
      return {
        aheader_menu: this.$root.aheader_menu,
      }
    }
  }
</script>
