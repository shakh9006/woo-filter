import single from "./sliders/single";
import multiple from "./sliders/multiple";
import { toggleValidator } from '@utils/index'

export default {
	props: {
		index: {
			default: null,
		},
		field: [Object, String],
	},

	components: {
		'single-slider'  : single,
		'multiple-slider': multiple,
	},

	data() {
		return {
			component_type: 'single-slider',
			rangeData: {},
		}
	},

	methods: {
		clone(data) {
			return JSON.parse(JSON.stringify(data))
		}
	},

	created() {
		if ( typeof this.field !== "undefined" ) {
			this.rangeData 		    = this.clone(this.field)
			this.rangeData.is_multi = toggleValidator(this.rangeData.is_multi)
			this.component_type     = this.rangeData.is_multi
				? 'multiple-slider'
				: 'single-slider'
		}
	},

	template: `
		<div class="wf-slider-wrapper">
			<component :is="component_type" :field="rangeData"></component>
		</div>
	`
}

