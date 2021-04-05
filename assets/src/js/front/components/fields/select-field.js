import { toggleValidator } from '@utils/index'
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
			selectData: null,
		}
	},

	created() {
		if ( this.field ) {
			this.selectData = this.field
			this.selectData.label_toggle = toggleValidator(this.selectData.label_toggle)
			this.selectData.is_multi	 = toggleValidator(this.selectData.is_multi)
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

		getSelectData() {
			let options = this.getStateByName(this.selectData.tag)
			options = options.filter((element) => {
				let used = this.selectData.used || []
				if (used.indexOf(element.id.toString()) !== -1) {
					return element
				}
			})

			return {
				options,
				multiple : this.selectData.is_multi,
			}
		}
	},

	template: `
			<div class="wf-select wf-field mb-10" v-if="selectData">
				<span class="wf-title wf-field-text" v-if="selectData.label_toggle">{{ selectData.title }}</span>
				<wf-select2 :key="'product-categories-' + selectData.id" @update="update" :content="getSelectData" :index="selectData.id" :selected="[]"></wf-select2>
			</div>
	`
}