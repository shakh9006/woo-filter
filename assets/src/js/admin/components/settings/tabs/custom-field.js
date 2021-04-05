import {mapGetters, mapMutations} from "vuex";
import draggable from 'vuedraggable'
import { toggleValidator } from '@utils/index';

export default {
	props: ['content'],

	components: {
		draggable,
	},

	data() {
		return {
			used_search: '',
		}
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
			return toggleValidator(value)
		},

		propertyExist(obj, name) {
			return obj && obj.hasOwnProperty(name)
		},

		update(value, index) {
			const usedFields = this.clone(this.usedFields) || []
			const field   	 = usedFields.find(( _, field_index) => field_index === index)
			const options 	 = field?.used || null

			if ( options && index ) {
				field.used = Array.from( value )
				usedFields[index] = field
				this.usedFields   = usedFields
			}
		},

		filterByInput(e, input) {
			const lowerName   = e.title?.toLowerCase()
			const lowerSearch = input?.toLowerCase()
			return lowerName.indexOf(lowerSearch) !== -1
		},
	},

	computed: {
		...mapGetters([
			'getPropertyByName'
		]),

		usedFields: {
			get() {
				let used = this.getPropertyByName('used')
				used = used.map(e => {
					if ( e.hasOwnProperty('is_multi') )
						e.is_multi = toggleValidator(e.is_multi)
					e.label_toggle = toggleValidator(e.label_toggle)
					return e
				})
				console.log('used: ', used)
				if ( this.used_search )
					return used.filter( e => this.filterByInput(e, this.used_search))

				return used || []
			},

			set(value) {
				this.setPropertyByName({name: 'used', value})
			}
		},

		logic_options: {
			get() {
				return this.getPropertyByName('logic_options')
			}
		},

		view_options: {
			get() {
				return this.getPropertyByName('view_options')
			}
		},

		getSortOptions() {
			const options = this.getPropertyByName('sort_type') || []
			return {
				options,
				multiple : true,
			}
		},

		getCategories() {
			const options = this.getPropertyByName('categories') || []
			return {
				options,
				multiple : true,
			}
		},

		getTags() {
			const options = this.getPropertyByName('tags') || []
			return {
				options,
				multiple : true,
			}
		}
	},

	template: `
		<div class="col-12">
			<div class="empty">
				<h3>Used Filter Fields</h3> 
			</div>
			<div class="wf-draggable-container" style="margin: 0 auto 30px;">
				<div class="wf-draggable-search">
					<input type="text" placeholder="Quick Search" v-model="used_search">
				</div>
				<draggable style="padding: 10px; min-height: 60px;" v-model="usedFields" handle=".handle" group="fields">
					<div class="wf-draggable-items" v-for="(element, element_index) in usedFields" :key="element_index">
						<div class="wf-draggable-panel-items-top">
							<div class="title">
								<span class="handle">
									<i class="icon--3"></i>
								</span>
								<p class="wf-default-text">
									<i :class="element.icon"></i>
									{{ element.title ? element.title : 'Empty' }} <b>({{ element.name }})</b>
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
										<span class="wf-title">Field Label</span> 
										<input placeholder="Enter Label" v-model="element.title" type="text">
									</div>
								</div>
								
								<div class="col-6">
									<div class="wf-admin-select wf-field">
										<span class="wf-title wf-admin-field-title">Select Type</span>
										<select class="wf-select-box wf-select-box-text wf-normalize" v-model="element.tag">
											<option v-for="(el_type, el_key) in element.types" :key="el_key" :value="el_key">{{ el_type }}</option>
										</select>
									</div>
								</div>
								
								<div class="col-6" v-if="propertyExist(element, 'min')">
									<div class="wf-input-field wf-field">
										<span class="wf-title">Min Value</span> 
										<input placeholder="Enter Min Value" v-model="element.min" type="number">
									</div>
								</div>
								
								<div class="col-6" v-if="propertyExist(element, 'max')">
									<div class="wf-input-field wf-field">
										<span class="wf-title">Max Value</span> 
										<input placeholder="Enter Max Value" v-model="element.max" type="number">
									</div>
								</div>
								
								<div class="col-6" v-if="propertyExist(element, 'step')">
									<div class="wf-input-field wf-field">
										<span class="wf-title">Step Value</span> 
										<input placeholder="Enter Step" v-model="element.step" type="number">
									</div>
								</div>
																
								<div class="col-6" v-if="propertyExist(element, 'placeholder')">
									<div class="wf-input-field wf-field">
										<span class="wf-title">Field Placeholder</span> 
										<input placeholder="Enter Placeholder" type="text" v-model="element.placeholder">
									</div>
								</div>
								
								<div class="col-6" v-if="propertyExist(element, 'logic')">
									<div class="wf-admin-select wf-field">
										<span class="wf-title wf-admin-field-title">Select Logic</span>
										<select class="wf-select-box wf-select-box-text wf-normalize" v-model="element.logic">
											<option v-for="(logic, logic_key) in logic_options" :key="logic_key" :value="logic_key">{{  logic }}</option>
										</select>
									</div>
								</div>
								
								<div class="col-6" v-if="propertyExist(element, 'view_type')">
									<div class="wf-title wf-admin-select wf-field">
										<span class="wf-admin-field-title">Select View Type</span>
										<select class="wf-select-box wf-select-box-text wf-normalize" v-model="element.view_type">
											<option v-for="(view, view_key) in view_options" :key="view_key" :value="view_key">{{ view }}</option>
										</select>
									</div>
								</div>
								
								<div class="col-6" v-if="propertyExist(element, 'is_multi')">
									<div class="wf-switch-field wf-field">
										<span class="wf-title" style="display: block; margin-bottom: 15px">Multiple</span>
										<label class="wf-switch">
											<input type="checkbox" v-model="element.is_multi" value="1">
											<div class="wf-slider wf-round"></div>
										</label>
										<span class="wf-title wf-switch-text wf-normalize"> {{ validate(element.is_multi) ? 'Enabled' : 'Disabled' }}</span>
									</div>
								</div>
								
								<div class="col-6" v-if="propertyExist(element, 'colour')">
									<div class="wf-switch-field wf-field">
										<span class="wf-title" style="display: block; margin-bottom: 15px">Pick Colour</span>
										<colour-picker v-model="element.colour" :value="element.colour" label="Pick Colour" picker="chrome"></colour-picker>
									</div>
								</div>
								
								<div class="col-6">
									<div class="wf-switch-field wf-field">
										<span class="wf-title" style="display: block; margin-bottom: 15px">Field Label On Frontend</span>
										<label class="wf-switch">
											<input type="checkbox" v-model="element.label_toggle" value="1">
											<div class="wf-slider wf-round"></div>
										</label>
										<span class="wf-title wf-switch-text wf-normalize"> {{ validate(element.label_toggle) ? 'Enabled' : 'Disabled' }}</span>
									</div>
								</div>

								<div class="col-6" v-if="element.tag === 'product_categories'">
									<div class="wf-admin-select wf-field">
										<span class="wf-title wf-field-text">Select Options</span>
										<wf-select2 :key="'product-categories-' + element_index" @update="update" :content="getCategories" :index="element_index" :selected="element.used"></wf-select2>
									</div>
								</div>
								
								<div class="col-6" v-if="element.tag === 'product_tags'">
									<div class="wf-admin-select wf-field">
										<span class="wf-title wf-field-text">Select Options</span>
										<wf-select2 :key="'product-tags-' + element_index"  @update="update" :content="getTags" :index="element_index" :selected="element.used"></wf-select2>
									</div>
								</div>
																	
								<div class="col-6" v-if="element.tag === 'sort_by'">
									<div class="wf-admin-select wf-field">
										<span class="wf-title wf-field-text">Select Options</span>
										<wf-select2 :key="'sort-by-' + element_index"  @update="update" :content="getSortOptions" :index="element_index" :selected="element.used"></wf-select2>
									</div>
								</div>
								
								<div class="col-12">
									<div class="wf-field"> 
										<div class="wf-text-area">
											<span class="wf-title wf-field-text">Enter Description</span>
											<textarea  v-model="element.description"></textarea>
										</div>
									</div>
								</div>		
							</div>
						</div>
					</div>
				 </draggable>
		  	 </div>
		</div>
	`
}