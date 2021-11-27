import '@stylus/main.styl'

import Axios from 'axios';
import Vue from 'vue'
import { App } from '@/app'
import router from '@/router'
import { VueSvgIcon } from '@yzfe/vue-svgicon'
import '@yzfe/svgicon/lib/svgicon.css'
import Auth from '@lib/auth'

Vue.config.productionTip = false

Vue.component('SvgIcon', VueSvgIcon)

Vue.prototype.$eventbus = new Vue();
Vue.prototype.$axios = Axios.create({
	baseURL: `${process.env.VUE_APP_API_ROUTE}/api`
})

Vue.use(new Auth())

new Vue({
	router,
	render: h => h(App)
}).$mount('#app')
