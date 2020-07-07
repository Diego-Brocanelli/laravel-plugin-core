<template>

    <div class="px-3" style="max-width: 900px">
      
        <h3 class="text-info">Sistema de plugins</h3>

        <p>
        O mecanismo de plugins se encontra no pacote 
        <a href="https://github.com/bueno-networks/laravel-plugin-core">"bnw/laravel-plugin-core"</a> 
        que deve ser instalado em um novo projeto Laravel.
        Uma vez adicionado o bnw/laravel-plugin-core, é possível 
        utilizar os recursos disponíveis para manipular o painel 
        e as páginas que serão implementadas.
        </p>

        <p>
        Para acessar o painel basta entrar no URI:
        </p>

        <pre><code>
            http://host-do-projeto-laravel/admin
        </code></pre>

        <h3 class="text-info pt-4">Tipo de projeto</h3>

        <p>
        Existem duas formas de utilizar o pacote bnw/laravel-plugin-core:
        </p>

        <h4 class="text-info">1. Em uma instalação Laravel</h4>

        <p>
        Para essa modalidade, basta instalar o Laravel normalmente 
        e adicionar o bnw/laravel-plugin-core como dependência.
        </p>

        <pre><code>
            composer create-project laravel/laravel meu-projeto
            php artisan key:generate
            chmod -Rf 777 bootstrap/cache 
            chmod -Rf 777 storage
            composer require bnw/laravel-plugin-core
        </code></pre>

        <h4 class="text-info">2. Em um novo plugin</h4>

        <p>
        O principal objetivo deste pacote é a possibilidade de 
        desenvolver aplicações Laravel de forma isolada, na forma 
        de plugins baseados em pacotes composer. 
        Os plugins criados se acoplam ao painel administrativo, 
        deixando a cargo do desenvolvedor apenas a preocupação 
        com o que interessa: as Regras de Negócio.
        </p>

        <p>
        Para criar uma ambiente de desenvolvimento para plugins, 
        basta executar as ações abaixo:
        </p>

        <pre><code>
            # Criando o diretório do projeto
            mkdir meu-projeto-laravel
            cd meu-projeto-laravel

            # Instalando o Laravel
            composer create-project laravel/laravel laravel
            php artisan key:generate
            chmod -Rf 777 bootstrap/cache 
            chmod -Rf 777 storage
            composer require bnw/laravel-plugin-core

            # Criando um módulo
            cd ../
            composer create-project bnw/laravel-plugin-example meu-plugin
        </code></pre>

        <p>
        O plugin de exemplo é um bom ponto de partida para a criação de novos
        plugins customizados. Para informações mais aprofundadas, por favor, 
        visite a documentação do pacote <a href="https://github.com/bueno-networks/laravel-plugin-example">bnw/laravel-plugin-example</a>
        </p>

        

    </div>
    
</template>

<script>
    export default {
        data() {
            return {
            }
        }
    }
    
</script>

<style scoped>

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