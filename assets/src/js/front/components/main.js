import input    from './fields/input-field'
import slider   from './fields/slider-field'
import checkbox from './fields/checkbox-field'
import color    from './fields/color-field'
import radio    from './fields/radio-field'
import rating   from './fields/rating-field'
import sort_by  from './fields/sort-by-field'


import { mapGetters, mapMutations } from "vuex"

export default {
	components: {
		'input-field'    : input,
		'slider-field'   : slider,
		'checkbox-field' : checkbox,
		'color-field'    : color,
		'radio-field'    : radio,
		'rating-field'   : rating,
		'sort_by-field'  : sort_by,
	},

	data() {
		return {
			fields: null
		}
	},

	created() {
		this.fields = this.getStateByName('fields') || []
	},

	computed: {
		...mapGetters([
			'getStateByName',
		])
	},

	methods: {
		...mapMutations([
			'setStateByName',
		])
	},

	template: `
		<div class="wf-container">
			<div class="wf-filter-wrapper" v-if="fields && fields.length > 0">
				<div class="filter-item" v-for="field in fields">
					<component :is="field.type + '-field'"></component>
				</div>
			</div>		
			<div class="wf-product-wrapper">
			
			</div>
		</div>
	`
}