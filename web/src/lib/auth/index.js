import Axios from 'axios'

const USER_AUTH = `user_auth`

export default class Auth {
	constructor(vue) {
		this.__vue = vue;
		this.axios = Axios.create({
			baseURL: `${process.env.VUE_APP_API_ROUTE}/api`,
			/*headers: {
				Authorization: `Bearer ${ this.__getUser() ? this.__getUser().token : '' }`
			}*/
		});
		if (this.__vue && this.__vue.$axios)
			this.__vue.$axios.defaults.headers.Authorization = `Bearer ${ this.__getUser() ? this.__getUser().token : '' }`;
		if (this.axios)
			this.axios.defaults.headers.Authorization = `Bearer ${ this.__getUser() ? this.__getUser().token : '' }`;
	}

	async login(data) {
		return await this.axios
		.post(`login`, data)
		.then(response => {
			this.__setUser(response.data)
			this.axios.defaults.headers.Authorization = `Bearer ${response.data.token}`
			if (this.__vue) {
				this.__vue.$eventbus.$emit('user-auth', true)
				this.__vue.$axios.defaults.headers.Authorization = `Bearer ${response.data.token}`
			}
			return Promise.resolve(response.data)
		})
		.catch(error => {
			this.__deleteUser()
			if (this.__vue) {
				this.__vue.$eventbus.$emit('user-auth', false)
			}
			return Promise.reject(error.response)
		})
	}

	async loginAdmin(data){
		return await this.axios
		.post(`login-admin`, data)
		.then(response => {
			this.__setUser(response.data)
			this.axios.defaults.headers.Authorization = `Bearer ${response.data.token}`
			if (this.__vue) {
				this.__vue.$eventbus.$emit('user-auth', true)
				this.__vue.$axios.defaults.headers.Authorization = `Bearer ${response.data.token}`
			}
			return Promise.resolve(response.data)
		})
		.catch(error => {
			this.__deleteUser()
			if (this.__vue) {
				this.__vue.$eventbus.$emit('user-auth', false)
			}
			return Promise.reject(error.response)
		})
	}

	async logout() {
		return await this.axios
		.get(`logout`)
		.finally(response => {
			this.__deleteUser()
			this.axios.defaults.headers.Authorization = `Bearer `
			if (this.__vue) {
				this.__vue.$eventbus.$emit('user-auth', false)
				this.__vue.$axios.defaults.headers.Authorization = `Bearer `
			}
			return Promise.resolve(response)
		})
	}

	async authenticated() {
		return await this.axios
		.get(`usuario`)
		.then(response => {
			let user = this.__getUser();
			let save = false;
			if(user.email !== response.data.email){
				user.email = response.data.email;
				save = true;
			}
			if(response.data.show_upgrade){
				user.show_upgrade = response.data.show_upgrade;
				user.upgrade_nivel = response.data.upgrade_nivel;
				user.selo = response.data.selo;
				user.old_selo = response.data.old_selo;
				save = true;
			}else{
				user.show_upgrade = false;
				save = true
			}
			if(save)
				this.__setUser(user);
			Promise.resolve(true);
		})
		.catch(() => {
			this.__deleteUser()
			return Promise.reject(false)
		})
	}

	async atualizar(data) {
		return await this.axios
		.post(`usuario/atualizar`, data)
		.then(response => Promise.resolve(response.data))
		.catch(error => Promise.reject(error.response))
	}

	async preCadastro(data) {
		return await this.axios
		.post(`usuario/pre-cadastro`, data)
		.then(response => Promise.resolve(response.data))
		.catch(error => Promise.reject(error.response))
	}

	async load() {
		let authenticated = await this.authenticated()
		if (authenticated)
			return await this.axios
			.get(`usuarios/perfil`)
			.then(response => Promise.resolve(response.data))
			.catch(error => Promise.reject(error.response))
		return {}
	}

	async recover(data) {
		return await this.axios
		.post(`password/email`, data)
		.then(response => Promise.resolve(response.data))
		.catch(error => Promise.reject(error.response))
	}

	async reset(data) {
		return await this.axios
		.post(`password/reset`, data)
		.then(response => Promise.resolve(response.data))
		.catch(error => Promise.reject(error.response))
	}

	get user() {
		let user = this.__getUser()
		if (user)
			delete user.token
		return user
	}

	__getUser() {
		let user = localStorage.getItem(USER_AUTH)
		if (user)
			return JSON.parse(user)
		return undefined
	}

	__setUser(user) {
		return localStorage.setItem(USER_AUTH, JSON.stringify(user))
	}

	__deleteUser() {
		return localStorage.removeItem(USER_AUTH)
	}

	install(Vue) {
		Vue.prototype.$auth = new this.constructor(new Vue())
	}
}
