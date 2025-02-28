window.LivewireUIModal=()=>({show:!1,showActiveComponent:!0,activeComponent:!1,componentHistory:[],modalWidth:null,listeners:[],getActiveComponentModalAttribute(e){if(this.$wire.get("components")[this.activeComponent]!==void 0)return this.$wire.get("components")[this.activeComponent].modalAttributes[e]},closeModalOnEscape(e){if(this.getActiveComponentModalAttribute("closeOnEscape")===!1||!this.closingModal("closingModalOnEscape"))return;let t=this.getActiveComponentModalAttribute("closeOnEscapeIsForceful")===!0;this.closeModal(t)},closeModalOnClickAway(e){this.getActiveComponentModalAttribute("closeOnClickAway")!==!1&&this.closingModal("closingModalOnClickAway")&&this.closeModal(!0)},closingModal(e){const t=this.$wire.get("components")[this.activeComponent].name;var o={id:this.activeComponent,closing:!0};return Livewire.dispatchTo(t,e,o),o.closing},closeModal(e=!1,t=0,o=!1){if(this.show===!1)return;if(this.getActiveComponentModalAttribute("dispatchCloseEvent")===!0){const n=this.$wire.get("components")[this.activeComponent].name;Livewire.dispatch("modalClosed",{name:n})}if(this.getActiveComponentModalAttribute("destroyOnClose")===!0&&Livewire.dispatch("destroyComponent",{id:this.activeComponent}),t>0)for(var i=0;i<t;i++){if(o){const n=this.componentHistory[this.componentHistory.length-1];Livewire.dispatch("destroyComponent",{id:n})}this.componentHistory.pop()}const s=this.componentHistory.pop();s&&!e?s?this.setActiveModalComponent(s,!0):this.setShowPropertyTo(!1):this.setShowPropertyTo(!1)},setActiveModalComponent(e,t=!1){if(this.setShowPropertyTo(!0),this.activeComponent===e)return;this.activeComponent!==!1&&t===!1&&this.componentHistory.push(this.activeComponent);let o=50;this.activeComponent===!1?(this.activeComponent=e,this.showActiveComponent=!0,this.modalWidth=this.getActiveComponentModalAttribute("maxWidthClass")):(this.showActiveComponent=!1,o=400,setTimeout(()=>{this.activeComponent=e,this.showActiveComponent=!0,this.modalWidth=this.getActiveComponentModalAttribute("maxWidthClass")},300)),this.$nextTick(()=>{var s;let i=(s=this.$refs[e])==null?void 0:s.querySelector("[autofocus]");i&&setTimeout(()=>{i.focus()},o)})},focusables(){return[...this.$el.querySelectorAll("a, button, input:not([type='hidden']), textarea, select, details, [tabindex]:not([tabindex='-1'])")].filter(t=>!t.hasAttribute("disabled"))},firstFocusable(){return this.focusables()[0]},lastFocusable(){return this.focusables().slice(-1)[0]},nextFocusable(){return this.focusables()[this.nextFocusableIndex()]||this.firstFocusable()},prevFocusable(){return this.focusables()[this.prevFocusableIndex()]||this.lastFocusable()},nextFocusableIndex(){return(this.focusables().indexOf(document.activeElement)+1)%(this.focusables().length+1)},prevFocusableIndex(){return Math.max(0,this.focusables().indexOf(document.activeElement))-1},setShowPropertyTo(e){this.show=e,e?document.body.classList.add("overflow-y-hidden"):(document.body.classList.remove("overflow-y-hidden"),setTimeout(()=>{this.activeComponent=!1,this.$wire.resetState()},300))},init(){this.modalWidth=this.getActiveComponentModalAttribute("maxWidthClass"),this.listeners.push(Livewire.on("closeModal",e=>{this.closeModal((e==null?void 0:e.force)??!1,(e==null?void 0:e.skipPreviousModals)??0,(e==null?void 0:e.destroySkipped)??!1)})),this.listeners.push(Livewire.on("activeModalComponentChanged",({id:e})=>{this.setActiveModalComponent(e)}))},destroy(){this.listeners.forEach(e=>{e()})}});
