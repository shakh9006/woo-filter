import {toggleValidator} from '@utils/index'

export default {
	props: {
		index: {
			default: null,
		},
		field: [Object, String],
	},

	data() {
		return {
			inputData: null,
		}
	},

	created() {
		if ( this.field ) {
			this.inputData = this.field
			this.inputData.label_toggle = toggleValidator(this.inputData.label_toggle)
		}
	},

	methods: {

	},

	computed: {

	},

	template: `
			<div class="wf-input-field wf-field mb-10">
				<span class="wf-input-title" v-if="inputData.label_toggle">{{ inputData.title }}</span>
				<input :placeholder="inputData.placeholder" type="number">
			</div>
	`
}