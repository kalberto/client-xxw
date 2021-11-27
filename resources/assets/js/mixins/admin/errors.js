let errors_mixin = {
    data: function () {
        return {
        	errors:[],
	        has_errors:[]
        }
    },
    methods:{
    	insertCustomErrors(key){
    		if(this.has_errors[key] !== undefined){
			    this.$nextTick(() => {this.insertErrors(this.has_errors[key]);});
		    }
	    },
    	hasError(toBeChecked){
    		if(this.has_errors[toBeChecked] !== undefined)
    			return true;
    		let hasError = false;
		    let properties = Object.getOwnPropertyNames(this.errors);
		    for(const key in properties){
		    	if(properties[key].includes(toBeChecked)){
		    		if(this.has_errors[toBeChecked] === undefined){
					    this.has_errors[toBeChecked] = [];
				    }
				    this.has_errors[toBeChecked][properties[key]] = this.errors[properties[key]];
				    hasError = true;
			    }
		    }
		    return hasError;
	    },
    	createErrorSpan(error){
    		let span = document.createElement('SPAN');
    		span.className = 'has-error';
    		span.appendChild(document.createTextNode(error));
    		return span;
	    },
        findAncestor(element,cls){
            let count = 0;
            let found = false;
            while(count <= 5){
                if(element.classList.contains(cls)){
                	found = true;
	                break;
                }
                element = element.parentElement;
                count++;
            }
            if(found)
                return element;
            else
                return found;
        },
	    insertMessages(messages, className){
		    let fields = Object.getOwnPropertyNames(messages);
		    if(Array.isArray(fields)){
			    for(const key in fields){
				    if(this.$refs[fields[key]]){
					    let element = Array.isArray(this.$refs[fields[key]]) ? this.$refs[fields[key]].length > 0 ? this.$refs[fields[key]][0] : false : this.$refs[fields[key]];
					    if(element === false)
						    break;
					    if(!element.classList.contains('underlined'))
						    element.className += ' underlined';
					    let ancestor = this.findAncestor(element,'form-group');
					    if(ancestor !== false){
						    if(!ancestor.classList.contains(className))
							    ancestor.className += ' ' + className;
						    let children = Array.from(ancestor.getElementsByClassName(className));
						    children.forEach(function (element) {
							    element.remove();
						    });
					    }
					    if(Array.isArray(messages[fields[key]])){
						    for(const msg in messages[fields[key]]){
							    if(messages[fields[key]].hasOwnProperty(msg))
								    element.parentElement.append(this.createErrorSpan(messages[fields[key]][msg]));
						    }
					    }else{
						    element.parentElement.append(this.createErrorSpan(messages[fields[key]]));
					    }
					    element.onfocus = this.focusEvent;
				    }
			    }
		    }
	    },
        insertErrors(errors){
	    	this.insertMessages(errors,'has-error');
        },
	    insertSuccess(messages){
		    this.insertMessages(messages,'has-success');
	    },
	    focusEvent(event){
            this.removeMessages(event.srcElement);
	    },
	    removeMessages(element){
		    element.classList.remove('underlined');
		    let ancestor = this.findAncestor(element,'form-group');
		    if(ancestor !== false){
		    	ancestor.classList.remove('has-success');
			    ancestor.classList.remove('has-error');
			    let children = Array.from(ancestor.querySelectorAll('span.has-error'));
			    children.forEach(function (element) {
				    element.remove();
			    });
		    }
		    element.onfocus = null;
	    }
    }
};