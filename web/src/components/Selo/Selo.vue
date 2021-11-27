<template lang="pug">
    #selo
        p Olá,
            strong {{ $auth.user.nome_fantasia }}
        //-img(:src="require('@images/selos/selo.png')")

</template>

<script>
export default {
    name: "component-selo",
}
</script>

<style lang="stylus" scoped src="./Selo.styl"></style>
