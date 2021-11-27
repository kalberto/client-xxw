<template lang="pug">
    #paginacao
        button(@click="$emit('loadPosts', params.page - 1)", :disabled="params.page < 2").back
            img(:src="require('@images/icons/arrow.png')")
            span Anterior
        ul
            li(v-for="page in params.total_pages", :class="{ ativo: page == params.page }", @click="$emit('loadPosts', page)")
                i

        button(@click="$emit('loadPosts', params.page + 1)", :disabled="params.page >= params.total_pages").next
            span Proximo
            img(:src="require('@images/icons/arrow.png')")
</template>

<script>
export default {
    name: "component-paginacao",
    props: {
        params: {
            required: true,
            type: Object,
        }
    },
}
</script>

<style lang="stylus" scoped src="./Paginacao.styl"></style>