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
			checkboxData: null,
			checkboxList: [],
		}
	},

	created() {
		if ( this.field ) {
			this.checkboxData = this.field
			this.checkboxData.label_toggle = toggleValidator(this.checkboxData.label_toggle)

			if ( this.value?.value ) {
				this.checkboxList = this.value?.value
				this.update()
			}
		}
	},

	methods: {
		update() {
			this.$emit('update', this.field.tag, this.checkboxList, this.field.logic)
		}
	},

	computed: {
		...mapGetters([
			'getStateByName'
		]),

		getCheckboxData() {
			let options = this.getStateByName(this.checkboxData.tag)
			options = options.filter((element) => {
				let used = this.checkboxData.used || []
				if (used.indexOf(element.id.toString()) !== -1) {
					element.id = element.id.toString()
					return element
				}
			})

			return options
		}
	},

	template: `
			<div class="wf-checkbox wf-field mb-10" v-if="checkboxData">
				<span class="wf-title wf-field-text" v-if="checkboxData.label_toggle">{{ checkboxData.title }}</span>
				<div class="wf-checkbox-list">
					<template v-if="checkboxData.view_type === 'default'">
						<div class="wf-checkbox-field"  v-for="checkbox in getCheckboxData" :key="checkbox.id">
							<input type="checkbox" :id="checkbox.id + '_checkbox'" @change="update" v-model="checkboxList" :value="checkbox.id"> 
							<label :for="checkbox.id + '_checkbox'">
								<span class="wf-checkbox-text uListing-normalize">
									{{ checkbox.text }}
								</span>
							</label>
						</div>		
					</template>
					<template v-else>
						<div class="wf-switch-field" v-for="toggle in getCheckboxData" :key="toggle.id">
							<label class="wf-switch">
								<input type="checkbox" :value="toggle.id" @change="update" v-model="checkboxList">
								<div class="wf-slider wf-round"></div>
							</label>
							<span class="wf-title wf-switch-text wf-normalize">{{ toggle.text }}</span>
						</div>
					</template>
					<p class="wf-field-description" v-if="checkboxData.description">{{ checkboxData.description }}</p>
				</div>
			</div>
	`
}