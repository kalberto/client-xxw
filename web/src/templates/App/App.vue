<template lang="pug">
    main#app
        SvgIcon(data="@svgs/logo_xxw.svg" v-if="$route.name == 'home'" original).logo
        vuescroll(:ops="vueScrollConfig", ref="vuescroll")
            router-view
</template>

<script>
import vuescroll from 'vuescroll/dist/vuescroll-native';

export default {
    name: "template-app",
    components: {
        vuescroll
    },
    data() {
        return {
            vueScrollConfig: {
                rail: {
                    size: '7px',
                },
                bar: {
                    background: '#d2af7f',
                    onlyShowBarOnScroll: false,
                    size: '6px',
                }
            }
        }
    }
}
</script>

<style lang="stylus" scoped src="./App.styl"></style>
