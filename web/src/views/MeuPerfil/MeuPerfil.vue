<template lang="pug">
	article#meuPerfil
		Header
		.container
			.resultado
				h2(v-if="user && user.nome_fantasia") Olá, {{user.nome_fantasia}}
				table
					tr(v-if="user && user.documento")
						td CNPJ
						td {{user.documento | maskCnpj}}
					tr(v-if="user && user.razao_social")
						td Razão Social
						td {{user.razao_social}}
					tr(v-if="user && user.nome_fantasia")
						td Nome Fantasia
						td {{user.nome_fantasia}}
					tr(v-if="user && user.cidade")
						td Cidade
						td {{user.cidade}}
					tr(v-if="user && user.estado")
						td Estado
						td {{user.estado}}
				p Caso preciso alterar alguns desses dados, entre em contato
			.dados
				p Dados de Contato
				.group
					input(:readonly="editar != '01'", @blur="editarInformacao('email')" v-model="userAtualizado.email")
					span(v-if="errors && errors.email") {{errors.email[0]}}
					button(@click="toggleEditar('01')")
						img(:src="require('@images/icons/edit.png')")
				.group
					input(:readonly="editar != '02'", @blur="editarInformacao('data_nascimento')" v-mask="['##/##/####']" v-model="userAtualizado.data_nascimento")
					span(v-if="errors && errors.data_nascimento") {{errors.data_nascimento[0]}}
					button(@click="toggleEditar('02')")
						img(:src="require('@images/icons/edit.png')")
				.group
					input(:readonly="editar != '03'", @blur="editarInformacao('contato_responsavel')" v-model="userAtualizado.contato_responsavel")
					span(v-if="errors && errors.contato_responsavel") {{errors.contato_responsavel[0]}}
					button(@click="toggleEditar('03')")
						img(:src="require('@images/icons/edit.png')")

				.group
					input(:placeholder="userAtualizado.celular ? userAtualizado.celular : 'Celular'"  v-mask="['## #####-####']", :readonly="editar != '04'", @blur="editarInformacao('celular')" v-model="userAtualizado.celular")
					span(v-if="errors && errors.celular") {{errors.celular[0]}}
					button(@click="toggleEditar('04')")
						img(:src="require('@images/icons/edit.png')")
				p(v-if="aviso") {{aviso}}
</template>

<script>
import Header from '@components/Header/Header'
import {mask} from 'vue-the-mask'

export default {
	name: "view-meu-perfil",
	components: {
		Header,
	},
	directives: {
		mask
	},
	data() {
		return {
			editar: null,
			user: null,
			userAtualizado: {
				email: null,
				data_nascimento: null,
				contato_responsavel: null,
				celular: null,
			},
			errors: null,
			aviso: null
		}
	},
	mounted(){
		this.loadUser()
	},
	filters: {
		maskCnpj(value){
			if(value)
				return value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5")
			else
				return ''
		}
	},
	methods: {
		toggleEditar(valor){
			this.editar = valor
		},
		editarInformacao(campo){
			this.editar = null
			if(this.userAtualizado[campo] != this.user[campo])
				this.atualizarUser()
		},
		loadUser() {
			this.$axios.get(`usuario/perfil-interno`)
			.then(response => {
				this.user = response.data.registro
				this.userAtualizado.email = this.user.email
				this.userAtualizado.data_nascimento = this.user.data_nascimento
				this.userAtualizado.contato_responsavel = this.user.contato_responsavel
				this.userAtualizado.celular = this.user.celular
			})
		},
		atualizarUser(){
			this.$axios.post(`usuario/salvar`, this.userAtualizado)
				.then(() => {
					this.aviso = "Enviado com sucesso!"
				})
				.catch(error => {
					// console.log(error.response.data.errors)
					this.errors = error.response.data.errors
				})
				.finally(() => {
					setTimeout(() => {
						this.aviso = null
					}, 5000)
				})
		}
	}

}
</script>

<style lang="stylus" scoped src="./MeuPerfil.styl"></style>