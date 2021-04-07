import {toggleValidator} from '@utils/index'
import {mapGetters} from "vuex";

export default {
	props: {
		index: {
			default: null,
		},
		field: [Object, String],
		value: {
			type: [String, Array, Object],
			default: function () {
				return []
			},
		}
	},

	data() {
		return {
			radioData: null,
			radioValue: '',
		}
	},

	created() {
		if ( this.field ) {
			this.radioData = this.field
			this.radioData.label_toggle = toggleValidator(this.radioData.label_toggle)
			if ( this.value?.value ) {
				let value = this.value.value
				if ( Array.isArray(value) && value.length > 0 ) {
					this.radioValue = value[0]
				} else {
					this.radioValue = ''
				}
			}
		}
	},

	methods: {
		update() {
			this.$emit('update', this.field.tag, [this.radioValue.toString()])
		}
	},

	computed: {
		...mapGetters([
			'getStateByName'
		]),

		getRadioData() {
			let options = this.getStateByName(this.radioData.tag)
			options = options.filter((element) => {
				let used = this.radioData.used || []
				if (used.indexOf(element.id.toString()) !== -1) {
					return element
				}
			})

			return options
		}
	},

	template: `
			<div class="wf-radio wf-field mb-10" v-if="radioData">
				<span class="wf-title wf-field-text" v-if="radioData.label_toggle">{{ radioData.title }}</span>
				<div class="wf-radio-list">
					<div class="wf-radio-item" v-for="radio in getRadioData">
						<input :id="'radio_test_' + radio.id" v-model="radioValue" @change="update" type="radio" name="test_radio_name" :value="radio.id">
						<label :for="'radio_test_' + radio.id">{{ radio.text }}</label>
					</div>
				</div>
			</div>
	`
}