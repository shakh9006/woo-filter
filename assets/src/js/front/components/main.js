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
			texts: null,
			loader: true,
			count: 0,
			product_template: '',
			filter_data  : {},
			product_categories: [],
			product_tags: [],
		}
	},

	created() {
		const ajaxUrl = this.getStateByName('ajaxUrl')
		const id 	  = this.getStateByName('id')
		getRequest(ajaxUrl, {action: 'wf_filter_front_data', id}, response => {
			const { settings, fields, product_categories, texts, product_tags, sort_by } = response?.data
			this.setStateByName({name: 'settings', value: settings})
			this.setStateByName({name: 'fields',   value: fields})
			/**
			 * Set Woo Data
			 */

			this.texts				= texts
			this.product_tags 		= product_tags
			this.product_categories = product_categories
			this.setStateByName({name: 'product_categories',   value: product_categories})
			this.setStateByName({name: 'product_tags',   	   value: product_tags})
			this.setStateByName({name: 'sort_by',   		   value: sort_by})
			this.renderDefaultValues()
			setTimeout(() => this.render_products())
		})
	},

	computed: {
		...mapGetters([
			'getStateByName',
		]),

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

		is_ajax() {
			return this.settings.is_ajax
		},

		get_class() {
			return this.settings.position
		},
	},

	methods: {
		...mapMutations([
			'setStateByName',
		]),

		renderDefaultValues() {
			const params = this.getSearchParameters()

			for ( let key in params ) {
				if ( key === 'orderby' )
					this.filter_data['sort_by'] = {type: key, value: params[key]}

				if ( key === 'rng_min_price' && !params.hasOwnProperty('rng_max_price') )
					this.filter_data['price'] = {type: key, value: [params.rng_min_price, 0]}
				else if ( key === 'rng_min_price' )
					this.filter_data['price'] = {type: key, value: [params.rng_min_price, params.rng_max_price] }

				if ( key === 'product_tag' || key === 'product_cat' ) {
					const type = key === 'product_tag' ? 'product_tags' : 'product_categories'
					let val = decodeURIComponent(params[key])
					val	= val.split(',')
					const result = []

					;(this.get_data(type) || []).forEach(tag => {
						if ( val.indexOf(tag.slug) !== -1 )
							result.push(tag.id.toString())
					});

					this.filter_data[type] = {type: key, value: result }
				}
			}
		},

		update(type, value, relation) {
			if ( type )
				this.filter_data[type] = { value, type, relation }
		},


		build_url(fields) {
			const urls = []
			;(fields || []).forEach(param => {
				if ( param.type === 'sort_by' )
					urls.push({key: 'orderby', value: param.value})

				if ( param.type === 'price' ) {
					if ( Array.isArray(param.value) ) {
						const [min, max] = param.value
						urls.push({key: 'rng_min_price', value: min})
						urls.push({key: 'rng_max_price', value: max})
					}

					if ( typeof param.value === "string" || typeof param.value === "number" )
						urls.push({key: 'rng_min_price', value: param.value})
				}

				if ( param.type === 'product_tags' || param.type === 'product_categories' ) {
					const name = param.type === 'product_tags' ? 'product_tag' : 'product_cat'
 					if ( Array.isArray(param.value) ) {
						const val = []
						;(this.get_data(param.type) || []).forEach(tag => {
							if ( param.value.indexOf(tag.id?.toString()) !== -1 )
								val.push(tag.slug)
						});
						urls.push({key: name, value: val.join(',')})
					}
				}
			});
			this.update_url(urls)
		},

		applyChanges() {
			this.build_url(Object.values(this.filter_data));
		},

		update_url(urls) {
			const url = new URL(window.location.href)
			urls.forEach(u => {
				if ( u.value !== '' )
					url.searchParams.set(u.key, u.value)
				else
					url.searchParams.delete(u.key)
			})
			window.history.replaceState(null, null, url.href);

			if ( this.is_ajax === 'no' ) {
				location.reload();
				return false
			} else {
				this.loader = true
				this.render_products()
			}
		},

		render_products() {
			const ajaxUrl = this.getStateByName('ajaxUrl')
			const id 	  = this.getStateByName('id')
			const params  = this.getSearchParameters()

			const relations 	= {}
			const relation_data = ['product_categories', 'product_tags']

			Object.keys(this.filter_data).forEach(key_name => {
				if ( relation_data.indexOf(key_name) !== -1 && ( params.hasOwnProperty('product_cat') ||  params.hasOwnProperty('product_tag') ) ) {
					let param_key = key_name === 'product_categories' ? 'product_cat' : 'product_tag'
					let current   = this.filter_data[key_name]
					if ( typeof params[param_key] !== "undefined" ) {
						relations[param_key] = current.relation
					}
				}
			})
			postRequest(ajaxUrl, {action: 'wf_filter_update', id, params, relations}, response => {
				const { products } = response
				this.product_template = products
				setTimeout(() => this.loader = false, 500)
			})
		},

		getSearchParameters() {
			let prm_str = window.location.search.substr(1);
			return prm_str != null && prm_str != "" ? this.transformToAssocArray(prm_str) : {};
		},

		transformToAssocArray( param_str ) {
			let params = {};
			let param_r = param_str.split("&");
			for ( let i = 0; i < param_r.length; i++) {
				let tm_parr = param_r[i].split("=");
				params[tm_parr[0]] = tm_parr[1];
			}
			return params;
		},

		get_data(type) {
			const data =  {
				product_categories: this.product_categories,
				product_tags: this.product_tags
			}

			if ( typeof data[type] !== "undefined" )
				return data[type]

			return []
		},

		clear_filter() {
			const params  = this.getSearchParameters()
			this.filter_data = {}
			Object.keys(params).forEach(name => this.delete_param(name))
		},

		delete_param(name) {
			let params = new URLSearchParams(location.search)
			params.delete(name)
			history.replaceState(null, '', '?' + params + location.hash)
		},
	},

	template: `
		<div class="wf-container container" >
			<div class="row" v-show="!loader" :class="get_class">
				<div class="wf-filter-wrapper col-4" v-if="fields && fields.length > 0">
					<div class="filter-item" v-for="(field, index) in fields">
						<component @update="update" :is="field.type + '-field'" :field="field" :key="index" v-model="filter_data[field.tag]"></component>
					</div>
					<button class="wf-btn" @click.prevent="applyChanges">{{ texts && texts.apply }}</button>
					<span style="display: block; cursor: pointer" @click.prevent="clear_filter">{{ texts && texts.reset }}</span>
				</div>		
				<div class="wf-product-wrapper col-8 woocommerce" v-html="product_template"></div>
			</div>
			<wf-loader v-if="loader"></wf-loader>
		</div>
	`
}