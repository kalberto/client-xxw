<template lang="pug">
    article#saibaMais(:class="{ form: $route.name != 'home' }")
        ul.access
            // li.login.firstAccess
            //     router-link(:to="{ name: 'login', query: { firstAccess: true } }") Primeiro acesso
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
                a(href="https://institucional.xxw.com.br/seja-um-autorizado?_ga=2.14408439.191777974.1626784017-766079419.1626784016", target="_blank") Seja um Serviço Autorizado
            .distribuidor
                router-link(:to="{ name: 'cadastroDistribuidor' }") Seja um distribuidor
        ul.vantagens
            li(v-for="vantagem, index in vantagens")
                div.extra
                    p {{vantagem.texto}}
                img(:src="vantagem.icon")
                p {{vantagem.titulo}}
        transition(name="formularios")
            router-view
</template>

<script>
export default {
    name: "view-saibamais",
    data() {
        return {
            vantagens: [
                {
                    icon: require('@images/icons/vantagens/vantagens_01.png'),
                    titulo: "Peças Originais xxw",
                    texto: "Descontos aplicados automaticamente na implantação dos pedidos, válido para as Peças Originais xxw."
                },
                {
                    icon: require('@images/icons/vantagens/vantagens_02.png'),
                    titulo: "Prazo de Pagamento Diferenciado",
                    texto: "Além de outros diversos benefícios, quem participa do programa tem prazos exclusivos para poder se organizar e realizar os pagamentos."
                },
                {
                    icon: require('@images/icons/vantagens/vantagens_03.png'),
                    titulo: "Rebates",
                    texto: "O Rebate é uma bonificação EXCLUSIVA para os participantes do Programa. Para ter direito, deve se assumir um account planning junto à xxw, comprometendo-se a aumentar seu volume de compras com base em seu histórico."
                },
                {
                    icon: require('@images/icons/vantagens/vantagens_04.png'),
                    titulo: "Verba de Propaganda Cooperada",
                    texto: "Ferramenta de apoio ao DISTRIBUIDOR para desenvolver ações para promoção e crescimento das vendas da marca."
                },
                {
                    icon: require('@images/icons/vantagens/vantagens_05.png'),
                    titulo: "Assessoria de Inteligência de Negócios",
                    texto: "Quer uma mãozinha para seu negócio ir ainda mais longe? Com o programa, nós disponibilizamos análises para ajudarnas suas tomadas de decisões e estratégias de negócios."
                },
                {
                    icon: require('@images/icons/vantagens/vantagens_06.png'),
                    titulo: "Dicas de planejamento",
                    texto: "Receba dicas exclusivas do nosso time para fazer um planejamento completo de vendas, finanças e muito mais assuntos relacionados aos seus negócios."
                },
                {
                    icon: require('@images/icons/vantagens/vantagens_07.png'),
                    titulo: "Saiba antes da concorrência",
                    texto: "Com o programa, você fica por dentro de tudo antes da concorrência e sai na frente para vender nossas novidades."
                },
            ]
        }
    }
}
</script>

<style lang="stylus" scoped src="./SaibaMais.styl"></style>
