<template lang="pug">
    article#home(:class="{ form: $route.name != 'home' }")
        ul.access
            li.login.firstAccess
                router-link(:to="{ name: 'login', query: { firstAccess: true } }") Primeiro acesso
            li.login
                router-link(:to="{ name: 'login' }") Login
        router-link(:to="{ name: 'home' }", tag="div" :class="{'login-mobile': $route.name == 'login', cadastro: $route.name == 'cadastro'}").relacionamento
            img(src="@images/logos/relacionamento-canais-xxw-v1.png" alt="Relacionamento de Canais xxw", title="Relacionamento de Canais xxw")
        .modelo
            img(src="@images/geral/modelo.png", alt="")
        .sucesso
            h2
                span.left O seu
                | Sucesso
                span.right
                    | é o que faz
                    br
                    | o nosso
            .saibamais
                router-link(:to="{ name: 'saibamais' }") Saiba Mais
        transition(name="formularios")
            router-view
</template>

<script>
export default {
    name: "view-home",
}
</script>

<style lang="stylus" scoped src="./Home.styl"></style>
