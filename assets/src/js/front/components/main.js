import input    from './fields/input-field'
import slider   from './fields/slider-field'
import checkbox from './fields/checkbox-field'
import color    from './fields/color-field'
import radio    from './fields/radio-field'
import rating   from './fields/rating-field'
import sort_by  from './fields/sort-by-field'
import select  from './fields/select-field'


import { mapGetters, mapMutations } from "vuex"
import { getRequest, postRequest } from '@utils/index'

export default {
	components: {
		'input-field'    : input,
		'slider-field'   : slider,
		'checkbox-field' : checkbox,
		'color-field'    : color,
		'radio-field'    : radio,
		'rating-field'   : rating,
		'sort_by-field'  : sort_by,
		'select-field'	 : select,
	},

	data() {
		return {
			product_template: '<div>No Available Products</div>',
		}
	},

	created() {
		const ajaxUrl = this.getStateByName('ajaxUrl')
		const id 	  = this.getStateByName('id')
		getRequest(ajaxUrl, {action: 'wf_filter_front_data', id}, response => {
			const { settings, fields, product_categories, product_tags, sort_by, products } = response?.data
			this.setStateByName({name: 'settings', value: settings})
			this.setStateByName({name: 'fields',   value: fields})
			this.product_template = products
			/**
			 * Set Woo Data
			 */
			this.setStateByName({name: 'product_categories',   value: product_categories})
			this.setStateByName({name: 'product_tags',   	   value: product_tags})
			this.setStateByName({name: 'sort_by',   		   value: sort_by})
		})
	},

	computed: {
		...mapGetters([
			'getStateByName',
		]),

		loader: {
			get() {
				return this.getStateByName('loader')
			},

			set(value) {
				this.setStateByName({name: 'loader', value})
			}
		},

		settings: {
			get() {
				return this.getStateByName('settings')
			},

			set(value) {
				this.setStateByName({name: 'settings', value})
			}
		},

		fields: {
			get() {
				return this.getStateByName('fields')
			},

			set(value) {
				this.setStateByName({name: 'fields', value})
			}
		},
	},

	methods: {
		...mapMutations([
			'setStateByName',
		])
	},

	template: `
		<div class="wf-container container">
			<div class="row">
				<div class="wf-filter-wrapper col-4" v-if="fields && fields.length > 0">
					<div class="filter-item" v-for="field in fields">
						<component :is="field.type + '-field'" :field="field"></component>
					</div>
				</div>		
				<div class="wf-product-wrapper col-8" v-html="product_template">
					
				</div>
			</div>
		</div>
	`
}