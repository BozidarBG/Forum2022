class FormSubmition{
    constructor(form_id, rules, request_type="ajax", callback=null, args=null ){
        this.form_id=form_id;
        this.formHtml=document.getElementById(this.form_id);
        this.rules=rules;
        this.route=this.formHtml.getAttribute('action');
        this.method=this.formHtml.getAttribute('method');
        this.password_value="";
        this.input_values={}; //filled with collectInputValuesFromForm();
        this.errorsObj={};//filled with checkForErrorsInForm()
        this.request_type=this.checkType(request_type);
        this.callback=callback;
        this.args=args;
        

        this.errorMessages={
            minLength: "FIELD_NAME must be at least REQUIRED_VALUE characters long.",
            maxLength: "FIELD_NAME cannot be more than REQUIRED_VALUE characters long.",
            email: "FIELD_NAME must be in email format.",
            number: "FIELD_NAME must be in the form of number.",
            password: "FIELD_NAME must contain at least one uppercase letter, lowercase letter, number and special character.",
            password_confirmation: "FIELD_NAME and password do not match.",
            file: "FIELD_NAME must be of a type REQUIRED_VALUE.",
            spec_chars_false: "FIELD_NAME cannot contain any of the special charactersREQUIRED_VALUE.",
            spec_chars_true: "FIELD_NAME must contain special charactersREQUIRED_VALUE.",
            color: "FIELD_NAME must be a type color.",
            date: "FIELD_NAME must be in valid format yyyy-mm-dd.",//"2021-06-15"
            time: "FIELD_NAME must be in valid format hh:ii.", //
            type:"FIELD_NAME must be of type REQUIRED_VALUE.",
            required:"FIELD_NAME is required.",
            values:"Chosen values for FIELD_NAME is not among values REQUIRED_VALUE.",
        };

        this.collectInputValuesFromForm();
        this.checkForErrorsInForm();
        //console.log(this.errorsObj)
        //console.log(this.input_values)

        return this;

    }
  
    sendPostViaAjax(){
        let form_data=new FormData();
        for (const [key, value] of Object.entries(this.input_values)) {
            form_data.append(key, value);
        }
        form_data.append('_token', document.getElementsByName('_token')[0].value);

        axios.post(this.route, form_data).then((data)=>{
            //console.log(data)
            if(this.callback){
                this.callback(data, this.args)
            }
           
            return data;
        });
    }

    hasErrors(){
        return !this.isObjectEmpty(this.errorsObj);
    }

    checkType(str){
        if(str.toLowerCase()==="ajax"){
            return "ajax";
        }else if(str.toLowerCase()==="http"){
            return "http";
        }else{
            console.log('type is not good')
        }
    }

    checkForErrorsInForm(){
        //loop through all this.rules keys
        for(let [input_name, object_rules_for_input] of Object.entries(this.rules)){
            //then loop through all rules for that key
            //(rule key is method name from this class i.e. minLength:2 = minLength, email:true= email(), etc
            //arg_value is value from rule key i.e 2 in minLength:2, true in email:true
            for(let [method_name, arg_value] of Object.entries(object_rules_for_input)){
                //console.log(input_name, method_name, arg_value)
                //do we have a method name in this class
                if(typeof this[method_name] === "function"){
                    //we call this method and check if it will return error msg or ""
                    //console.log(this.input_values[input_name], input_name, arg_value);
                    let error_message=this[method_name](this.input_values[input_name], input_name, arg_value);
                    //console.log(error_message)
                    //check if there is an error message, if there is, populate this.errors
                    this.putErrorsInErrorsObject(error_message, input_name)
                }else{
                    //we don't have such method so we throw alert
                    console.log('There is no method name '+method_name)
                }
            }
        }
    }

    //check for errors and if any, put them in this.errorsObj
    putErrorsInErrorsObject(error_message, input_name){
        //console.log(error_message, input_name)
        if(error_message.length){
            //if we don't have error for this rule so we need to add input_key to object
            if(!this.errorsObj.hasOwnProperty(input_name)){
                this.errorsObj[input_name]=[];
            }
            //we add error message to inputObj.key
            this.errorsObj[input_name].push(error_message);
        }
    }

    //collects input, textarea, select...
    //must have class "fc"
    collectInputValuesFromForm() {
        let input_values={};
        for(let i=0; i<this.formHtml.length; i++){
            if(this.formHtml[i].classList.contains('fc')){
                //console.log(this.formHtml[i])
                //select tag is different from other tags
                if((this.formHtml[i].tagName).toLowerCase() ==="select" && this.formHtml[i].hasAttribute('multiple')){
                    const selected = this.formHtml[i].querySelectorAll('option:checked');
                    const values = Array.from(selected).map(el => el.value.trim());
                    input_values[this.formHtml[i].name]=[values];
                 }
                else if(this.formHtml[i].type==="file"){
                    //this.formHtml[i].value; //daje C:\fakepath\bookmarks_10_13_19.html
                    //console.log(this.formHtml[i].files[0])
                    if(this.formHtml[i].files[0] !==undefined){
                        //console.log(this.rules[this.formHtml[i].name])
                        input_values[this.formHtml[i].name]=this.formHtml[i].files[0];
                    }
                }
                else if(this.formHtml[i].type==="checkbox" || this.formHtml[i].type==="radio"){
                    //console.log(this.formHtml[i].name) //vehicle
                    if(this.formHtml[i].checked){
                        //console.log(this.formHtml[i].value, this.formHtml[i].name) //
                        //we are only interested if input is checked
                        //see if there is among input_values key with name. must value for that name must be array
                        let name_of_checkbox=this.formHtml[i].name;
                        let value_of_checkbox=this.formHtml[i].value.trim();
                        if(input_values.hasOwnProperty(name_of_checkbox)){
                            input_values[name_of_checkbox].push(value_of_checkbox);
                        }else{
                            input_values[name_of_checkbox]=[value_of_checkbox];
                        }
                    }
                }
                else{
                    // input_values{name:"James Harden", password:"secret"}
                    input_values[this.formHtml[i].name]=this.formHtml[i].value.trim();
                    if(this.formHtml[i].name==="password"){
                        this.password_value=this.formHtml[i].value.trim();
                    }
                }

            }
        }
        this.input_values=input_values;
    }


    isObjectEmpty(obj) {

        if(this.errorsObj===undefined || this.errorsObj===null){
            return true;
        }
        return Object.keys(obj).length === 0 && obj.constructor === Object;
    }



    putErrorsInSingleDiv(divId, errorsObject, arrayOfClasses, tagName="p"){
        let targetDiv=document.getElementById(divId);
        if(targetDiv.classList.contains('errors')){
            targetDiv.classList.remove('errors');
            targetDiv.style.flexDirection="column";
        }
        targetDiv.innerHTML="";
        for(let [input_name, arrayOfErrors] of Object.entries(errorsObject)){

            for(let i=0; i<arrayOfErrors.length; i++){
                targetDiv.appendChild(this.createErrorMessageWithTag(tagName, arrayOfClasses, arrayOfErrors[i]))
            }

        }

    }


    putErrorsBellowEveryInput(errorsObject, arrayOfClasses, tagName="p"){
        //console.log(errorsObject, arrayOfClasses, tagName)
        this.deleteErrorMessages();

        for(let [input_name, arrayOfErrors] of Object.entries(errorsObject)){
            let inputTag=document.querySelectorAll("[name="+input_name+"]")[0]
            let parentEl=inputTag.parentElement;
            for(let i=0; i<arrayOfErrors.length; i++){
                parentEl.appendChild(this.createErrorMessageWithTag(tagName, arrayOfClasses, arrayOfErrors[i]))
            }
        }
    }

    putErrorsAboveEveryInput(errorsObject, arrayOfClasses, tagName="p"){
        //console.log(errorsObject, arrayOfClasses, tagName)
        this.deleteErrorMessages();

        for(let [input_name, arrayOfErrors] of Object.entries(errorsObject)){
            let inputTag=document.querySelectorAll("[name="+input_name+"]")[0]
            let parentEl=inputTag.parentElement;
            for(let i=0; i<arrayOfErrors.length; i++){
                parentEl.prepend(this.createErrorMessageWithTag(tagName, arrayOfClasses, arrayOfErrors[i]));           }
        }
    }

    deleteErrorMessages(){
        let errorMessages=document.querySelectorAll('.fc-errors');
        if(errorMessages.length){
            for(let i=0; i<errorMessages.length; i++){
                errorMessages[i].remove();
            }
        }

    }
/*
    //errorsObj is {username:['error msg1', 'error msg2'], name: ['error msg1']}
    //adds <span class="text-danger>error msg1</span> after input field
    showErrorMessages(){

        if(this.formHtml && !this.isObjectEmpty(this.errorsObj)){
            for (const errors_key in this.errorsObj) {
                //u formi svi koji imaju name="big_text" npr (tj.samo prvi i jedini)
                let input_tag=this.formHtml.querySelector("[name="+errors_key+"]");
                this.errorsObj[errors_key].map((msg)=>{
                    let error_message=this.createErrorMessageWithTag('p', ['fc-error','text-danger'], msg);

                    //input_tag.insertAdjacentHTML('afterend', error_message);
                    //input_tag.parentElement.appendChild(error_message)
                    let parentEl=input_tag.parentElement;
                    let input_tag_next=input_tag.nextElementSibling;
                    parentEl.insertBefore(error_message, input_tag_next);
                });
            }
        }

    }

 */
/*
    showErrorMessagesFromBackend(messages_array){
        let messages="";
        for(let i=0; i<messages_array.length; i++){
            messages += this.createErrorMessageWithTag('span', 'text-danger', messages_array[i])
        }
        this.backendErrorsHtml.innerHTML=messages
    }
*/
    createErrorMessageWithTag(tag,arrOfClasses, message){
        let tagElement=document.createElement(tag);
        tagElement.classList.add('fc-errors');
        for(let i=0; i<arrOfClasses.length; i++){
            tagElement.classList.add(arrOfClasses[i]);
        }
        tagElement.textContent=message;
        return tagElement;
    }



    values(field_values, field_name, required_values){
        //console.log(field_values, field_name, required_values)
        let wrong_field_values=[];
        if(Array.isArray(field_values) && Array.isArray(required_values)){
            for(let i=0; i<field_values.length; i++){
                if(required_values.indexOf(field_values[i])<0){
                    //chosen value is not in array
                    wrong_field_values.push(field_values[i]);
                }
            }
        }
        if(wrong_field_values.length>0){
            let msg=this.errorMessages['values'];
            //"Chosen values for FIELD_NAME is not among values REQUIRED_VALUE.",
            let requiredValues=required_values.join(', ');
            this.generateErrorMessage('values', field_name, required_values);
        }else{
            return "";
        }
    }

    required(field_value, field_name) {
        //let error_msg=this.errorMessages['required'].replace('FIELD_NAME', field_name.charAt(0).toUpperCase() + field_name.slice(1));
        let error_msg=this.generateErrorMessage('required', field_name);

        //we must check if field is type file. no need to check other types so far
        let user_input_type=this.formHtml.querySelector("[name="+field_name+"]").type;
        if(user_input_type==="file"){
            if(this.input_values.hasOwnProperty(field_name)){
                return "";
            }
            else{
                //input_values:fajl doesn't exist so it is an error

                return error_msg;
            }

            //nije type file
        }else if(user_input_type==="checkbox" || user_input_type==="radio"){
            //they have multiple input fields with the same name so we need to see if min one is checked
            //to be required
            if(this.input_values.hasOwnProperty(field_name)){
                return "";
            }
            else{
                return error_msg;
            }

        }else {
            if(field_value.length>0){
                return "";
            }
            else{
                return error_msg;
            }
        }
    }

    number(field_value, field_name){
        console.log(field_value, field_name)
        if(isNaN(field_value)){
            return this.generateErrorMessage('number', field_name);
        }
        return "";
    }

    password(field_value, field_name){
        const password_pattern=/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s)/;
        if(!password_pattern.test(field_value)){
            this.generateErrorMessage('password', field_name);
        }
        return "";
    }

    minLength(field_value, field_name, required_length){
        //console.log(field_value)
        if(field_value.length < required_length){
            return this.generateErrorMessage('minLength', field_name, required_length);
        }
        return '';
    }

     maxLength(field_value, field_name, required_length){
         if(field_value.length > required_length){
             return this.generateErrorMessage('maxLength', field_name, required_length);
         }
         return '';
    }

    email(field_value, field_name) {
        const email_pattern=/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if(!email_pattern.test(field_value)){
            return this.generateErrorMessage('email', field_name);
        }
        return "";
    }

    type(field_value, field_name, required_types) {
        //ako nema, field_value is undefined
        if(field_value!==undefined){
            //console.log(field_value, field_name, required_types)
            if(this.input_values.hasOwnProperty(field_name)){
                let extension=field_value.name.split('.').pop();
                if(Array.isArray(required_types)){
                    if(required_types.includes(extension.toLowerCase())){
                        return "";
                    }else{
                        let chars=required_types.join(', ')
                        return this.generateErrorMessage('type', field_name, chars);
                    }
                }
            }
        }
        return "";

    }
/*
    //must contain at least one of special chars, or any special char if required_value_array is empty
    spec_chars_true(field_value, field_name, required_value_array) {
        //console.log(field_value, field_name, required_value_array)
        let chars="";
        let msg=this.errorMessages['spec_chars_true'];
        if(required_value_array.length>0){
            //we have some particular special chars that string must have

            for(let i=0; i<required_value_array.length; i++){
                //we check if any of these characters is in the string. if it is, we break the loop and return ""
                if(field_value.includes(required_value_array[i])){
                    return "";
                }
            }
            chars=" "+required_value_array.join(', ');
            return msg.replace('FIELD_NAME', field_name.charAt(0).toUpperCase() + field_name.slice(1)).replace('REQUIRED_VALUE', chars);
        }else{
            //array is empty so string can have any special chars
            const c_pattern=/(?=.*\W)/;
            if(!c_pattern.test(field_value)){
                return msg.replace('FIELD_NAME', field_name.charAt(0).toUpperCase() + field_name.slice(1)).replace('REQUIRED_VALUE', chars);
            }
        }

        return '';
    }
    */
/*
    //must not contain special chars from required_value_array. if array is empty, then string can't contain any spec chars
    spec_chars_false(field_value, field_name, required_value_array) {
        let chars="";
        let msg=this.errorMessages['spec_chars_false'];
        if(required_value_array.length>0){
            //string can't contain special chars that are in array
            for(let i=0; i<required_value_array.length; i++){
                //we check if any of these characters is in the string. if it is, we break the loop and return error message
                if(field_value.includes(required_value_array[i])){
                    chars=" "+required_value_array.join(', ');
                    return msg.replace('FIELD_NAME', field_name.charAt(0).toUpperCase() + field_name.slice(1)).replace('REQUIRED_VALUE', chars);
                }
            }
        }else{
            //string can't contain any special chars
            const characters_pattern=/(?=.*\W)/;
            if(characters_pattern.test(field_value)){
               //there is special char
                return msg.replace('FIELD_NAME', field_name.charAt(0).toUpperCase() + field_name.slice(1));
            }else{
                return "";
            }

        }
    }
*/
    password_confirmation(field_value, field_name) {
        if(this.password_value !== field_value){
            return this.generateErrorMessage('password_confirmation', field_name);
        }
        return "";
    }

    color(field_value, field_name) {
        const color_pattern=/#[a-zA-Z0-9]{6}|rgb\((?:\s*\d+\s*,){2}\s*[\d]+\)|rgba\((\s*\d+\s*,){3}[\d\.]+\)|hsl\(\s*\d+\s*(\s*\,\s*\d+\%){2}\)|hsla\(\s*\d+(\s*,\s*\d+\s*\%){2}\s*\,\s*[\d\.]+\)/
        if(!color_pattern.test(field_value)){
            return this.generateErrorMessage('color', field_name);
        }
        return "";
    }
    date(field_value, field_name) {
        const date_pattern=/^\d{4}\-(0?[1-9]|1[012])\-(0?[1-9]|[12][0-9]|3[01])$/;
        if(!date_pattern.test(field_value)){
            return this.generateErrorMessage('date', field_name);
           //return msg.replace('FIELD_NAME', field_name.charAt(0).toUpperCase() + field_name.slice(1));
        }
        return "";
    }
    time(field_value, field_name) {
        const time_pattern=/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/;
        if(!time_pattern.test(field_value)){
            return this.generateErrorMessage('time', field_name);
        }
        return "";
    }

    generateErrorMessage(func_name, field_name, field_value){
        let msg=this.errorMessages[func_name];
        let newFieldName=field_name.replace(/_/g, " ");
        return msg.replace('FIELD_NAME', newFieldName.charAt(0).toUpperCase() + newFieldName.slice(1)).replace('REQUIRED_VALUE', field_value);
    }

}



//click,  someclass, class, doThis
//click, tagname, tag, doThis
class Listener{
    constructor(eventName, selectorName, selectorType, functionToBeExecutedInCaseOfEvent){
        this.eventName=eventName;
        this.selectorName=selectorName;
        this.selectorType=this.determineSelectorType(selectorType);
        this.callback=functionToBeExecutedInCaseOfEvent;
        this.addEventListenerToElements();
    }

    //class or id or tag
    determineSelectorType(type){
        if(type.toLowerCase() ==="id"){
            return 'id';
        }else if(type.toLowerCase() ==="class"){
            return 'class';
        }else if(type.toLowerCase() ==="tag"){
            return 'tag';
        }else{
            throw 'Valid selector type is not submitted';
        }
    }

    addEventListenerToElements(){

        if(this.selectorType==="class"){
            let items=document.getElementsByClassName(this.selectorName);
            for(let i=0; i<items.length; i++){
                items[i].addEventListener(this.eventName, (e)=> {
                    e.preventDefault();
                    this.callback(e);
                });
            }
        }else if(this.selectorType==="tag"){
            let items=document.getElementsByTagName(this.selectorName);
            for(let i=0; i<items.length; i++){
                items[i].addEventListener(this.eventName, (e)=> {
                    e.preventDefault();
                    this.callback(e);
                });
            }
        }
        else{
            document.getElementById(this.selectorName).addEventListener(this.eventName,  (e)=> {
                e.preventDefault();
                this.callback(e);
            })
        }
    }

}

/* other functions */
function putErrorsInSingleDiv(divId, errorsArr, arrayOfClasses, tagName="p"){
    let targetDiv=document.getElementById(divId);
    if(targetDiv.classList.contains('fc-errors')){
        targetDiv.classList.remove('fc-errors');
    }
    targetDiv.innerHTML="";

    for(let i=0; i<errorsArr.length; i++){
        targetDiv.appendChild(this.createErrorMessageWithTag(tagName, arrayOfClasses, errorsArr[i]))
    }

}

function createErrorMessageWithTag(tag,arrOfClasses, message){
    let tagElement=document.createElement(tag);
    tagElement.classList.add('fc-errors');
    for(let i=0; i<arrOfClasses.length; i++){
        tagElement.classList.add(arrOfClasses[i]);
    }
    tagElement.textContent=message;
    return tagElement;
}

/* end other functions */
/*
script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    <script>
    

*/
class ImageUploader{
    constructor(url, settings, callback){
        this.url=url;
        this.croppie;
        this.modal;;
        this.input;
        this.el; 
        this.settings=settings || {
                width: 400,
                height: 400,
                type: 'square'
            };
        this.cb=callback;
        this.populateVars();
        this.addListeners();   
    }
    showModalWithSettings(e){
            this.modal.style.setProperty("display", "block", "important");
           
            this.getImage(e.target);
        
    }
    populateVars(){
        this.modal=this.getModalHtml();
        this.el=this.getResizer();
        this.initializeCroppie();
    }

    initializeCroppie(){
 // Initailize croppie instance and assign it to global variable
        this.croppie = new Croppie(this.el, {
            viewport: this.settings,
            boundary: {
                width: 300,
                height: 300
            },
            enableOrientation: true
        });
    }

    addListeners(){
        document.getElementById('file-upload').addEventListener('change', (event)=>{
            this.showModalWithSettings(event);
        });
        document.getElementById('upload').addEventListener('click', ()=>{
            this.uploadImage();
        });
        document.getElementById('closeModal').addEventListener('click', ()=>{
            this.closeModal();
        });
        let rotates=document.getElementsByClassName('rotate');
        for(let i=0; i<rotates.length; i++){
            rotates[i].addEventListener('click', (e)=>{
                this.rotateImage(e);
            });
        }
    }
 
    closeModal(){
        this.modal.style.setProperty("display", "none", "important");
    }

    getModalHtml(){
        return document.getElementById('croopModal');
    }
    getResizer(){
        return document.getElementById('resizer');
    }
    
    
    base64ImageToBlob(str) {
        // extract content type and base64 payload from original string
        let pos = str.indexOf(';base64,');
        let type = str.substring(5, pos);
        let b64 = str.substr(pos + 8);
        // decode base64
        let imageContent = atob(b64);
        // create an ArrayBuffer and a view (as unsigned 8-bit)
        let buffer = new ArrayBuffer(imageContent.length);
        let view = new Uint8Array(buffer);
        // fill the view, using the decoded base64
        for (let n = 0; n < imageContent.length; n++) {
            view[n] = imageContent.charCodeAt(n);
        }
        // convert ArrayBuffer to Blob
        let blob = new Blob([buffer], { type: type });
        return blob;
    }
    
    getImage(input){
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = (e)=> {
                this.croppie.bind({
                    url: e.target.result,
                });
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    uploadImage(){
        this.croppie.result('base64').then((base64)=> {
            this.closeModal();
            let formData = new FormData();
            formData.append("image", this.base64ImageToBlob(base64));
            let x=axios.post(this.url, formData).then((data)=>{
                this.cb(data.data.uspeh);
            });
        });
    }
    // To Rotate Image Left or Right
    rotateImage(e){
        let deg=e.target.hasAttribute('data-deg') ? e.target.getAttribute('data-deg') : e.target.parentElement.getAttribute('data-deg');
        this.croppie.rotate(parseInt(deg));
    }       


}
/*
//call class
request has "image" name
let url="{{ url('/store-image') }}";
let set={
                width: 300,
                height: 400,
                type: 'circle'
            };
const doSomething=(x)=>{
    console.log(x);
}            
new ImageUploader(url,set, doSomething);
*/
/*
//html for ImageUploader
<div class="text-center">
    <div class="btn btn-dark">
        <input type="file" class="file-upload" id="file-upload"
                name="image" accept="image/*">
                @csrf
        Upload New Photo
    </div>
</div>
<!-- The Modal -->
    <div class="modal" id="croopModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Crop Image And Upload</h4>
                    <button type="button" class="close" id="closeModal" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div id="resizer"></div>
                    <button class="btn rotate float-lef" data-deg="90" >
                        <i class="fas fa-undo"></i></button>
                    <button class="btn rotate float-right" data-deg="-90" >
                        <i class="fas fa-redo"></i></button>
                    <hr>
                    <button class="btn btn-block btn-dark" id="upload" >
                        Crop And Upload</button>
                </div>
            </div>
        </div>
    </div>
// end html for image uploader
*/