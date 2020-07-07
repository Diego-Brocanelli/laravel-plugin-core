# Manipulando o painel

O painel pode ser manipulado em tempo de execução. A cada carregamento de uma [página .vue](](paginas.md)) o painel pode sofrer alterações em seu layout. 

Isso pode ser feito de duas maneiras:

## Através da aplicação Vuejs

Dentro da página .vue, usando `this.$root` ou `app` é possível invocar a aplicação a fim de utilizar os recursos disponíveis.

Por exemplo:

```javascript
<script>
    export default {

        data() {
            return {
            }
        },
        methods: {
            changePanel: function (message) {

                // Usando a notação do vue
                this.$root.panel().disableSidebarLeft()

                // Usando diretamente a aplicação
                app.panel().disableSidebarLeft()
            }
        }
    }
</script>
```

Para descobrir todas as funcionalidades do painel, siga para a [API Javascript](api-js.md).


## Através da aplicação Laravel

Dentro do controlador, antes de liberar a página .vue, é possível usar as bibliotecas do mecanismo de plugins para acessar os recursos disponíveis.

Por exemplo:

```php

public function index(Request $request)
{
    // Especifica o nome da página que será exibido no topo
    $this->pageTitle('Este será o título da página');

    // Define os links de navegação para a página 
    $this->breadcrumb()
        ->append(new Entry('Pagina', '/example/page'));

    // Adiciona um novo item de menu quando esta página for carregada
    $this->sidebar()
        ->append(new Entry('Item Dinâmico', '/admin/home', 'emoji-smile'));

    // isso liberará a página 'resources/vue/paginas/form.vue'
    return vue('paginas.form');
}
```

Para conhecer todas as funcionalidades do mecanismo de plugins, siga para a [API PHP](api-php.md).


## Mais informações

[API Javascript](api-js.md)

[Voltar para o início](../readme.md)
