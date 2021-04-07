import { toggleValidator } from '@utils/index'
import { mapGetters } from "vuex";

export default {
	props: {
		index: {
			default: null,
		},
		field: [Object, String],
		value: {
			type: [String, Array, Object],
			default: function () {
				if ( toggleValidator(this.field.is_multi) )
					return []
				else return ''
			},
		}
	},

	data() {
		return {
			selectData: null,
			defaultValue: [],
		}
	},

	created() {
		if ( this.field ) {
			this.selectData = this.field
			this.selectData.label_toggle = toggleValidator(this.selectData.label_toggle)
			this.selectData.is_multi	 = toggleValidator(this.selectData.is_multi)
			this.defaultValue 			 = this.selectData.is_multi ? [''] : ''

			if ( this.value?.value ) {
				if ( this.selectData.is_multi ) {
					this.defaultValue = this.value?.value
				} else {
					if ( Array.isArray(this.value.value) && this.value.value.length > 0 ) {
						this.defaultValue = this.value.value[0]
					}
				}
			}
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

		getSelectData() {
			let options = this.getStateByName(this.selectData.tag)
			options = options.filter((element) => {
				let used = this.selectData.used || []
				if ( used.indexOf(element.id.toString()) !== -1 )
					return element
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
				<wf-select2 :key="'product-categories-' + selectData.id"  :content="getSelectData" :index="selectData.id" :selected="defaultValue" @update="update"></wf-select2>
			</div>
	`
}