import { mapGetters, mapMutations } from 'vuex'

export default {
	props: ['tabs'],

	computed: {
		...mapGetters([
			'getPropertyByName',
		]),

		get_tab: {
			get() {
				return this.getPropertyByName('active_tab')
			},

			set(value) {
				this.setPropertyByName({name: 'active_tab', value})
			}
		}
	},

	methods: {
		...mapMutations([
			'setPropertyByName',
		]),
	},

	template: `
		<div class="wf-settings-sidebar">
			<div class="wf-settings-menu">
				<ul>
					<li v-for="(tab, key) in tabs" :class="{active: get_tab === tab.component}" :key="key" @click="get_tab = tab.component">
						<i :class="tab.icon"></i>
						{{ tab.title }}
					</li>
				 </ul>
			</div>
		</div>
	`
}