<template>

    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="title m-b-md" @click="testeNaitis()">
                Página qualquer
            </div>
        </div>

        <sidebar-right-content title="Teste de título">

            <b-form-group
                id="input-group-1"
                label="Email address:"
                label-for="input-1"
                description="We'll never share your email with anyone else."
            >

              <b-form-input
              id="input-1"
              type="email"
              required
              placeholder="Enter email"
              @click="testeNaitis()"
              ></b-form-input>

            </b-form-group>

            <b-form-group
                id="input-group-1"
                label="Email address:"
                label-for="input-1"
                description="We'll never share your email with anyone else."
            >
              <b-form-input
              id="input-2"
              type="email"
              required
              placeholder="Enter email"
              ></b-form-input>

            </b-form-group>

        </sidebar-right-content>
    </div>

</template>

<script>
    export default {
        data() {

            // Isso troca o cabeçalho pelo componente 'core-admin-footer'
            // app.panel().changeComponent('aheader', 'core-admin-footer')

            // Isso desativa a barra lateral esquerda
            // app.panel().disableSidebarLeft();

            // app.panel().populateSidebarRight('<h1>Que legal</h1>');

            return {

            }
        },
        methods: {
            testeNaitis: function(){
                console.log('testou');
            }
        }
    }
    
</script>

<style scoped>

    html, body {
        background-color: #fff;
        color: #636b6f;
        font-weight: 100;
        height: 100vh;
        margin: 0;
    }

    .full-height {
        height: 50vh;
    }

    .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
    }

    .position-ref {
        position: relative;
    }

    .top-right {
        position: absolute;
        right: 10px;
        top: 18px;
    }

    .content {
        text-align: center;
    }

    .title {
        font-size: 84px;
    }

    .links > a {
        color: #636b6f;
        padding: 0 25px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: .1rem;
        text-decoration: none;
        text-transform: uppercase;
    }

    .m-b-md {
        margin-bottom: 30px;
    }
</style>