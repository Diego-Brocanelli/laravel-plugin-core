# Implementando Páginas

## Funcionamento

O painel administrativo se comunica com o Laravel através de invocações AJAX, que devolvem metadados no formato JSON. 

Para criar páginas CRUD (formulários, grades de dados ou quaisquer páginas DOM) deve-se criar arquivos .vue no diretório 'resources/vue' e liberá-los através dos controladores do Laravel (semelhante à forma convencional onde as views do blade são liberadas).

Em um controlador implementado no Laravel, ao invés de usar o helper `view()`, deve-se usar o helper `vue()`:

```php

public function index(Request $request)
{
    // isso liberará a página 'resources/vue/paginas/form.vue'
    return vue('paginas.form');
}

```

Essas páginas são invocadas por AJAX e renderizadas no centro do painel de forma dinâmica. Isso é possível porque, após o carregamento da resposta AJAX, os conteúdos são tratados como Componentes Single-File no Vue.js, ou seja, são compilados pelo mecanismo `vue-template-compiler` e aplicados como componentes no painel.

Abaixo, um exemplo de componente carregado por AJAX: 

```html
<template>

    <div class="px-3">
      
        <h3 class="text-info">Página HTML</h3>

        <p>
        Para acessar o painel basta entrar no URI:
        </p>

        <pre><code>
            http://host-do-projeto-laravel/admin
        </code></pre>

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
```

## Limitações das páginas dinâmicas 

Como as páginas são carregadas sob demanda (por AJAX), ainda não é possível utilizar imports, pois no momento de sua compilação não existe o escopo necessário para isso.

Em futuras evoluções isso poderá mudar, mas no momento essa é uma limitação conhecida. Sendo assim, a **implementação abaixo não funcionará**:

```
<script>
    import OtherComponent from './OtherComponent.vue'

    export default {
        data() {
            return {
            }
        }
    }
</script>
```

Para o objetivo do mecanismo de plugins, essa limitação é irrelevante, pois a ideia é limitar o uso de bibliotecas para que estas sejam definidas de forma segura e padronizada, disponibilizadas automaticamente pela aplicação de forma a suprir as necessidades de qualquer projeto baseado no painel.

## Mais informações

[Manipulando o painel](docs/painel.md)

[Voltar para o início](../readme.md)
