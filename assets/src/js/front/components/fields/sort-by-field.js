import {toggleValidator} from '@utils/index'
import { mapGetters } from "vuex";

export default {
	props: {
		index: {
			default: null,
		},
		field: [Object, String],
		value: {
			default: '',
		}
	},

	data() {
		return {
			sortData: null,
			defaultValue: '',
		}
	},

	created() {
		if ( this.field ) {
			this.sortData = this.field
			this.sortData.label_toggle = toggleValidator(this.sortData.label_toggle)
			if ( this.value )
				this.defaultValue = this.value?.value || ''
		}
	},

	methods: {
		update(value) {
			this.$emit('update', this.field.tag, value)
		}
	},

	computed: {
		...mapGetters([
			'getStateByName'
		]),

		getSortData() {
			let options = this.getStateByName('sort_by')
			options = options.filter((element) => {
				let used = this.sortData.used || []
				if (used.indexOf(element.id.toString()) !== -1) {
					return element
				}
			})

			return {
				options,
				multiple : false,
			}
		}
	},

	template: `
			<div class="wf-select wf-field mb-10" v-if="sortData">
				<span class="wf-title wf-field-text" v-if="sortData.label_toggle">{{ sortData.title }}</span>
				<wf-select2 :key="'product-categories-' + sortData.id" @update="update" :content="getSortData" :index="sortData.id" :selected="defaultValue"></wf-select2>
				<p class="wf-field-description" v-if="sortData.description">{{ sortData.description }}</p>
			</div>
	`
}