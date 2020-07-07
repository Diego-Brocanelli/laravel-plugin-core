<template>

    <div class="px-3" style="max-width: 900px">
      
        <h3 class="text-info">Bem-vindo</h3>

        <p>
        Este é o painel administrativo, uma aplicação Vue.js para
        desenvolvimento baseado em uma arquitetura de plugins.
        Como você já notou, o painel está limpo, sem itens de menu ou 
        quaisquer configurações.
        </p>

        <p>
        A tarefa de preenchê-las é sua!
        </p>

        <h4 class="text-info">Documentação</h4>

        <p>
        Para começar a criar páginas e personalizar o painel, por favor, acesse a documentação 
        do <a href="https://github.com/bueno-networks/laravel-plugin-core" target="_blank">"bnw/laravel-plugin-core"</a> 
        </p>

        <h4 class="text-info">Plugin de exemplo</h4>

        <p>
        Para melhorar o entendimento, existe um <a href="https://packagist.org/packages/bnw/laravel-plugin-example" target="_blank">plugin de exemplo</a>, 
        que você pode instalar facilmente através do comando:
        </p>

        <pre><code>
            composer require bnw/laravel-plugin-example
        </code></pre>

        <p>
        Você também pode conferir o código fonte do plugin de exemplo no 
        <a href="https://github.com/bueno-networks/laravel-plugin-example" target="_blank">repositório dele</a>. 
        </p>

    </div>
    
</template>

<script>
    export default {
        mount(){

            app.pages().setPageTitle('BNW Laravel Plugin Core')

            app.pages().setBreadcrumbItems({
                "home": {
                    "label":"Home",
                    "url":"\/core\/welcome"
                },
                "boas-vindas": {
                    "label":"Boas Vindas",
                    "url":"\/core\/welcome"
                }
            })
        }
    }
    
</script>

<style scoped>

    .page-wrapper h4 {
        margin-top: 20px;
    }

    .page-wrapper p {
      color: rgba(0,0,0,0.6);
      font-size: 1.2rem;
      margin-bottom: 0.5rem;
    }

    .page-wrapper pre {
        border-radius: 5px;
        background-color: rgba(0,0,0, 0.8);
        color: #fff;
    }
</style>