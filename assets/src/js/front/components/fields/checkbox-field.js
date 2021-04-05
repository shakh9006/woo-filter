import {toggleValidator} from '@utils/index'
import {mapGetters} from "vuex";

export default {
	props: {
		index: {
			default: null,
		},
		field: [Object, String],
	},

	data() {
		return {
			checkboxData: null,
		}
	},

	created() {
		if ( this.field ) {
			console.log('field: ', this.field)
			this.checkboxData = this.field
			this.checkboxData.label_toggle = toggleValidator(this.checkboxData.label_toggle)
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

		getCheckboxData() {
			let options = this.getStateByName(this.checkboxData.tag)
			options = options.filter((element) => {
				let used = this.checkboxData.used || []
				if (used.indexOf(element.id.toString()) !== -1) {
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
							<input type="checkbox" :id="checkbox.id + '_checkbox'" :value="checkbox.id"> 
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
								<input type="checkbox" :value="toggle.id">
								<div class="wf-slider wf-round"></div>
							</label>
							<span class="wf-title wf-switch-text wf-normalize">{{ toggle.text }}</span>
						</div>
					</template>
				</div>
			</div>
	`
}