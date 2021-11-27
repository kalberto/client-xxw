<template lang="pug">
    article#bemVindo
        SvgIcon(:data="LogoS", original)
        .opcoes
            p
                | O ambiente digital para você fazer suas compras, otimizar suas vendas, consultar especialistas e garantir benefícios exclusivos para comprar os produtos originais xxw.
            .link
                router-link(:to="{ name: 'faq' }") Perguntas Frequentes
            .link
                router-link(:to="{ name: 'dashboard' }") Dashboard
            .link
                router-link(:to="{ name: 'posts' }") Eventos

</template>

<script>
export default {
    name: "view-bem-vindo",
}
</script>

<style lang="stylus" scoped src="./BemVindo.styl"></style>
