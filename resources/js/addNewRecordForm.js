import { createApp } from 'vue'
import SelectDateTime from './components/SelectDateTime.vue'
import SelectLits from './components/SelectLits.vue'


createApp({})
.component('selec-date-time', SelectDateTime)
.mount('#selec-date-time')

createApp({})
.component('selec-lits', SelectLits)
.mount('#selec-lits')
