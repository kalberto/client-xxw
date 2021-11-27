import Vue from 'vue'
import Router from 'vue-router'
import Auth from '@lib/auth'

Vue.use(Router)

const beforeEnterAuthenticated = (to, from, next) => {
	if (typeof to.query.redirecting !== 'undefined')
		return next();
	const auth = new Auth();
	auth.authenticated()
		.then(() => {
			if(auth.user && auth.user.conta_atualizada){
				if(auth.user.show_upgrade && to.name !== 'avisoNivel'){
					next({name:'avisoNivel', query: { redirecting: '' }});
				}else{
					next()
				}
			}
			else{
				next({name:'cadastro', query: { redirecting: '' }});
			}
		})
		.catch(() => next({ name: 'login', query: { redirecting: '' } }))
};

const beforeEnterNotAuthenticated = (to, from, next) => {
	if (typeof to.query.redirecting !== 'undefined')
		return next();
	const auth = new Auth();

	auth.authenticated()
		.then(() => {
			if(auth.user && auth.user.conta_atualizada){
				if(auth.user.show_upgrade){
					next({name:'avisoNivel', query: { redirecting: '' }});
				}else{
					next({ name: 'dashboard', query: { redirecting: '' } })
				}
			}else{
				next({name:'cadastro', query: { redirecting: '' }});
			}
		})
		.catch(() => {
			if (typeof to.query.token == 'undefined' && to.name == 'novaSenha')
				next('/')
			else
				next();
		})
};

let routes = [
	{
		path: '/',
		name: 'home',
		component: () => import( /* webpackChunkName: "home" */ './views/Home/Home.vue'),
		beforeEnter: beforeEnterNotAuthenticated,
		children: [
			{
				path: 'cadastro',
				name: 'cadastro',
				component: () => import( /* webpackChunkName: "cadastro" */ './views/Cadastro/Cadastro.vue'),
				beforeEnter: beforeEnterAuthenticated,
			},
			{
				path: 'cadastro-distribuidor',
				name: 'cadastroDistribuidor',
				component: () => import( /* webpackChunkName: "cadastro" */ './views/CadastroDistribuidor/Cadastro.vue'),
				beforeEnter: beforeEnterNotAuthenticated,
			},
			{
				path: 'login',
				name: 'login',
				component: () => import( /* webpackChunkName: "login" */ './views/Login/Login.vue'),
				beforeEnter: beforeEnterNotAuthenticated
			},
			{
				path: 'recuperacao',
				name: 'recuperacao',
				component: () => import( /* webpackChunkName: "recuperacao" */ './views/Recuperacao/Recuperacao.vue'),
			},
			{
				path: 'nova-senha',
				name: 'novaSenha',
				component: () => import( /* webpackChunkName: "novaSenha" */ './views/NovaSenha/NovaSenha.vue'),
				beforeEnter: beforeEnterNotAuthenticated
			},
			{
				path: 'bem-vindo',
				name: 'bemVindo',
				component: () => import( /* webpackChunkName: "bemVindo" */ './views/BemVindo/BemVindo.vue'),
				beforeEnter: beforeEnterAuthenticated,
			},
		]
	},
	{
		path: '/saiba-mais',
		name: 'saibamais',
		component: () => import( /* webpackChunkName: "saibamais" */ './views/SaibaMais/SaibaMais.vue'),
		beforeEnter: beforeEnterNotAuthenticated,
	},
	{
		path: '/aviso-nivel',
		name: 'avisoNivel',
		component: () => import( /* webpackChunkName: "faq" */ './views/AvisoNivel/AvisoNivel.vue'),
		beforeEnter: beforeEnterAuthenticated,
	},
	{
		path: '/faq',
		name: 'faq',
		component: () => import( /* webpackChunkName: "faq" */ './views/Faq/Faq.vue'),
		beforeEnter: beforeEnterAuthenticated,
	},
	{
		path: '/nova-vpc',
		name: 'nova-vpc',
		component: () => import( /* webpackChunkName: "novaVpc" */ './views/VPC/NovaVPC.vue'),
		beforeEnter: beforeEnterAuthenticated,
	},
	{
		path: '/lista-vpc',
		name: 'listaVpc',
		component: () => import( /* webpackChunkName: "listaVpc" */ './views/ListaVpc/ListaVpc.vue'),
		beforeEnter: beforeEnterAuthenticated,
	},
	{
		path: '/vpc/:id',
		name: 'vpc',
		component: () => import( /* webpackChunkName: "vpc" */ './views/VPC/VPC.vue'),
		beforeEnter: beforeEnterAuthenticated,
	},
	{
		path: '/meu-perfil',
		name: 'meuPerfil',
		component: () => import( /* webpackChunkName: "meuPerfil" */ './views/MeuPerfil/MeuPerfil.vue'),
		beforeEnter: beforeEnterAuthenticated,
	},
	{
		path: '/dashboard',
		name: 'dashboard',
		component: () => import( /* webpackChunkName: "dashboard" */ './views/DashboardMini/DashboardMini.vue'),
		beforeEnter: beforeEnterAuthenticated,
	},
	{
		path: '/dashboard-detalhada',
		name: 'dashboardDetalhada',
		component: () => import( /* webpackChunkName: "dashboard" */ './views/Dashboard/Dashboard.vue'),
		beforeEnter: beforeEnterAuthenticated,
	},
	{
		path: '/posts',
		name: 'posts',
		component: () => import( /* webpackChunkName: "posts" */ './views/Posts/Posts.vue'),
		beforeEnter: beforeEnterAuthenticated,
	},
	{
		path: '/posts/:slug',
		name: 'post',
		component: () => import( /* webpackChunkName: "post" */ './views/Post/Post.vue'),
		beforeEnter: beforeEnterAuthenticated,
	},
];

routes.push({
	path: '/*',
	name: '404',
	redirect: '/',
});

let router = new Router({
	mode: 'history',
	base: `${process.env.VUE_APP_PUBLICPATH}`,
	routes,
});

export default router
