import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const baseUrl = '../../../views/'

const router = new VueRouter({
    routes: [
        //{path: '/', redirect: '/invoices'},
        {path: '/admin/administradores', component: require(baseUrl+'admin/administradores/index.vue')}
    ]
})

export default router