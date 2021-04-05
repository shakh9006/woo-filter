import {toggleValidator} from '@utils/index'
import { mapGetters } from "vuex";

export default {
	props: {
		index: {
			default: null,
		},
		field: [Object, String],
	},

	data() {
		return {
			sortData: null,
		}
	},

	created() {
		if ( this.field ) {
			this.sortData = this.field
			this.sortData.label_toggle = toggleValidator(this.sortData.label_toggle)
		}
	},

	methods: {
		update(value, index) {

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
				<wf-select2 :key="'product-categories-' + sortData.id" @update="update" :content="getSortData" :index="sortData.id" :selected="[]"></wf-select2>
			</div>
	`
}