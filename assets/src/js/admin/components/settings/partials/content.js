import { mapGetters, mapMutations } from 'vuex'

import customField from '../tabs/custom-field'
import settings    from '../tabs/settings'
import customize   from '../tabs/customize'

export default {
	props: ['content',  'logic_options', 'view_options', 'categories', 'tags'],

	data() {
		return {
			prop_data: {},
		}
	},

	components: {
		'custom-fields' : customField,
		'settings-tab'  : settings,
		'customize-tab' : customize,
	},

	created() {
		if ( typeof this.content !== "undefined" ) {
			this.prop_data.fields = this.content.fields
		}
	},
	
	computed: {
		...mapGetters([
			'getPropertyByName'
		]),
	},

	methods: {
		...mapMutations([
			'setPropertyByName'
		]),
	},
	
	template: `
		<div class="wf-settings-content">
			<div class="container">
				<div class="row">
					<keep-alive>
						<component :content="prop_data" :is="getPropertyByName('active_tab')"></component>
					</keep-alive>
				</div>
			</div>
		</div>
	`
}