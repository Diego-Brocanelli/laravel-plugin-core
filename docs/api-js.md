# Api Javascript

A API Javascript pode ser acessada dentro das páginas .vue dinâmicas:

```javascript
<script>
    export default {

        data() {

            app.request().get(...)

            return {
            }
        },

        mount() {

            app.loadingStart()
            app.request().get(...)
            app.loadingEnd()

        },

        methods: {
            sidebarOff: function (message) {

                app.panel().disableSidebarLeft()
            }
        }
    }
</script>
```

## app.request() 

Esta chamada devolve a instancia da biblioteca [Axios](https://github.com/axios/axios), utilizada para chamadas AJAX dentro do sistema.

Permite efetuar chamadas personalizadas (POST, PUT, DELETE etc).

## app.panel() 

Esta chamada devolve o gerenciador do painel administrativo. Os métodos disponíveis são:

### loadingStart() e loadingEnd()

Exibe e oculta o carregador na página

```javascript
app.loadingStart()
app.request().get(...)
app.loadingEnd()
```

### changeComponent(changeable, componentName)

Permite trocar dinamicamente os componentes no layout do painel. Os parâmetros aceitos são:  

- *changeable*: é a referência de um componente dinâmico do painel; 
- *componentName*: deve ser o nome do componente que será aplicado como substituto.  
 
As referências de componentes dinâmicos disponiveis para substituição são:

- **aheader**: o cabeçalho do painel
- **afooter**: o rodapé do painel
- **lsidebar**: a barra lateral esquerda
- **msidebar**: a barra lateral para dispositivos móveis
- **rsidebar**: a barra lateral direita
- **pheader**: o cabeçalho da página
- **page**: a área onde a página será aplicada

### enableSidebarLeft() e disableSidebarLeft()

Permite controlar a exibição da barra lateral esquerda do painel.

### enableSidebarRight() e disableSidebarRight()

Permite controlar a exibição da barra lateral direita do painel.

## app.pages() 

Esta chamada devolve o gerenciador do páginas .vue. Os métodos disponíveis são:

### setPageTitle(title)

Muda o nome da página que paarece no topo da área de páginas do painel.

### setBreadcrumbItems(items)

Define a lista de links de navegação para a página atual.

```javascript 

app.setBreadcrumbItems({
    "home": {
        "label":"Home",
        "slug":"home",
        "icon":"",
        "url":"\/example\/home",
        "status":"common",
        "type":"item"
    },
    "boas-vindas": {
        "label":"Boas Vindas",
        "slug":"boas-vindas",
        "icon":"",
        "url":"\/core\/welcome",
        "status":"common",
        "type":"item"
    }
})

```

### fetchHomePage() 

Carrega a página inicial definida para o sistema. Para mias informações sobre como definir a página inicial, acesse a [API PHP](docs/api-php.md).

### fetchPage(route)

Carrega uma página .vue existente na rota especificada. 
Ex: '/example/pagina'


## app.assets() 

Esta chamada devolve o gerenciador de scripts e styles. Os métodos disponíveis são:

### appendScriptUrl(url)

Adiciona no DOM uma tag script apontando para um arquivo javascript externo. Esse script estará em execução apenas no contexto da página atual. Ao carregar uma nova página, um novo escopo será executado.

### appendStyleUrl(url)

Adiciona no DOM uma tag link apontando para um arquivo de estilos css externo. Esse link estará em vigor apenas no contexto da página atual. Ao carregar uma nova página, um novo escopo será executado.

## Mais informações

[API PHP](docs/api-php.md)

[Voltar para o início](../readme.md)