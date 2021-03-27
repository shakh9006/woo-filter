import {mapGetters, mapMutations} from "vuex";
import draggable from 'vuedraggable'
import { toggleValidator } from '@utils/index';

export default {
	props: ['content', 'logic_options', 'view_options'],

	components: {
		draggable,
	},

	methods: {
		...mapMutations([
			'setPropertyByName'
		]),

		remove(index) {
			const fields    = this.clone(this.usedFields)
			this.usedFields = fields.filter((_, i) => i !== index)
		},

		clone(value) {
			return JSON.parse(JSON.stringify(value))
		},

		openPanel(index) {
			const used = this.clone(this.usedFields)
			if (used[index].is_open)
				used[index].is_open = false
			else {
				used.forEach(item => item.is_open = false)
				used[index].is_open = true
			}
			this.usedFields = used
		},

		validate(value, name) {
			console.log('name: ', name)
			console.log('val: ', value, toggleValidator(value), typeof toggleValidator(value))
			console.log('==========')

			return toggleValidator(value)
		},

		propertyExist(obj, name) {
			return obj && obj.hasOwnProperty(name)
		}
	},

	computed: {
		...mapGetters([
			'getPropertyByName'
		]),

		usedFields: {
			get() {
				console.log('used: ', this.getPropertyByName('used'))
				return this.getPropertyByName('used')
			},

			set(value) {
				this.setPropertyByName({name: 'used', value})
			}
		}
	},

	template: `
		<div class="col-12">
			<div class="wf-draggable-container" v-if="usedFields.length > 0" style="margin: 35px auto;">
				<draggable v-model="usedFields" handle=".handle" group="fields">
					<div class="wf-draggable-items" v-for="(element, element_index) in usedFields" :key="element.id">
						<div class="wf-draggable-panel-items-top">
							<div class="title">
								<span class="handle">
									<i class="icon--3"></i>
								</span>
								<p class="wf-default-text">
									<i :class="element.icon"></i>
									{{ element.label ? element.label : 'Empty' }} <b>({{ element.name }})</b>
								</p>
							</div>
							<div class="action p-r-10">
								<span @click.prevent="remove(element_index)" class="btn remove">
									<i class="icon-3096673"></i>
								</span>
								<span class="btn toggle" @click.prevent="openPanel(element_index)">
									<i v-if="element.is_open" class="icon--360"></i>
									<i v-if="!element.is_open" class="icon--54"></i>
								</span>
							</div>
						</div>
						<div class="wf-draggable-panel-items-inside container accordion-content" v-show="validate(element.is_open)">
							<div class="row">
								
								<div class="col-6">
									<div class="wf-input-field wf-field">
										<span>Field Label</span> 
										<input placeholder="Enter Label" v-model="element.label" type="text">
									</div>
								</div>
								
								<div class="col-6">
									<div class="wf-admin-select wf-field">
										<span class="wf-admin-field-title">Select Type</span>
										<select class="wf-select-box wf-select-box-text wf-normalize" v-model="element.tag">
											<option v-for="(el_type, el_key) in element.types" :key="el_key" :value="el_key">{{ el_type }}</option>
										</select>
									</div>
								</div>
								
								<div class="col-6" v-if="propertyExist(element, 'min')">
									<div class="wf-input-field wf-field">
										<span>Min Value</span> 
										<input placeholder="Enter Min Value" v-model="element.min" type="number">
									</div>
								</div>
								
								<div class="col-6" v-if="propertyExist(element, 'max')">
									<div class="wf-input-field wf-field">
										<span>Max Value</span> 
										<input placeholder="Enter Max Value" v-model="element.max" type="number">
									</div>
								</div>
								
								<div class="col-6" v-if="propertyExist(element, 'step')">
									<div class="wf-input-field wf-field">
										<span>Step Value</span> 
										<input placeholder="Enter Step" v-model="element.step" type="number">
									</div>
								</div>
																
								<div class="col-6" v-if="propertyExist(element, 'placeholder')">
									<div class="wf-input-field wf-field">
										<span>Field Placeholder</span> 
										<input placeholder="Enter Placeholder" type="text" v-model="element.placeholder">
									</div>
								</div>
								
								<div class="col-6" v-if="propertyExist(element, 'logic')">
									<div class="wf-admin-select wf-field">
										<span class="wf-admin-field-title">Select Logic</span>
										<select class="wf-select-box wf-select-box-text wf-normalize" v-model="element.logic">
											<option v-for="(logic, logic_key) in logic_options" :key="logic_key" :value="logic_key">{{  logic }}</option>
										</select>
									</div>
								</div>
								
								<div class="col-6" v-if="propertyExist(element, 'view_type')">
									<div class="wf-admin-select wf-field">
										<span class="wf-admin-field-title">Select View Type</span>
										<select class="wf-select-box wf-select-box-text wf-normalize" v-model="element.view_type">
											<option v-for="(view, view_key) in view_options" :key="view_key" :value="view_key">{{ view }}</option>
										</select>
									</div>
								</div>
								
								<div class="col-6" v-if="propertyExist(element, 'is_multi')">
									<div class="wf-switch-field wf-field">
										<span style="display: block; margin-bottom: 15px">Multiple</span>
										<label class="wf-switch">
											<input type="checkbox" v-model="element.is_multi" value="1">
											<div class="wf-slider wf-round"></div>
										</label>
										<span class="wf-switch-text wf-normalize"> {{ validate(element.is_multi) ? 'Enabled' : 'Disabled' }}</span>
									</div>
								</div>
								
								<div class="col-6" v-if="propertyExist(element, 'colour')">
									<div class="wf-switch-field wf-field">
										<span style="display: block; margin-bottom: 15px">Pick Colour</span>
										<colour-picker v-model="element.colour" :value="element.colour" label="Pick Colour" picker="chrome"></colour-picker>
									</div>
								</div>
								
								<div class="col-6">
									<div class="wf-switch-field wf-field">
										<span style="display: block; margin-bottom: 15px">Field Label On Frontend</span>
										<label class="wf-switch">
											<input type="checkbox" v-model="element.label_toggle" value="1">
											<div class="wf-slider wf-round"></div>
										</label>
										<span class="wf-switch-text wf-normalize"> {{ validate(element.label_toggle) ? 'Enabled' : 'Disabled' }}</span>
									</div>
								</div>
								
								<div class="col-12">
									<div class="wf-field"> 
										<div class="wf-text-area">
											<span class="wf-field-text">Enter Description</span>
											<textarea  v-model="element.description"></textarea>
										</div>
									</div>
								</div>		
							</div>
						</div>
					</div>
				 </draggable>
		  	 </div>
			 <div class="empty" v-else>
				<h3 style="padding: 30px">It's empty here. Add new fields for building your filter.</h3> 
			</div>
		</div>
	`
}