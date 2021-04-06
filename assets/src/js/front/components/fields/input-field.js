import {toggleValidator} from '@utils/index'

export default {
	props: {
		index: {
			default: null,
		},
		field: [Object, String],
		value: {
			default: null
		},
	},

	data() {
		return {
			inputData: null,
			inputValue: 0,
		}
	},

	created() {
		if ( this.field ) {
			this.inputData = this.field
			this.inputData.label_toggle = toggleValidator(this.inputData.label_toggle)
			if ( this.value ) {
				const [left, _] = this.value?.value || [0, 0]
				this.inputValue = left
				this.update()
			}
		}
	},

	methods: {
		update() {
			this.$emit('update', this.field.tag ,this.inputValue)
		},
	},

	computed: {

	},

	template: `
			<div class="wf-input-field wf-field mb-10">
				<span class="wf-input-title" v-if="inputData.label_toggle">{{ inputData.title }}</span>
				<input :placeholder="inputData.placeholder" type="number" v-model="inputValue" @change="update" @input="update">
			</div>
	`
}