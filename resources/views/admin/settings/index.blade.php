@extends('admin.partials.admin-layout')
@section('styles')
    <style>
        .color-table{
            width: 4rem;
            height: 2rem;
        }
    </style>
    @endsection

@section('content')
    <div class="row">


@include('admin.partials.confirmation-modal')

        <div class="col-md-8">
            @include('admin.partials.success-errors')
            <div id="backend_delete_errors"></div>
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title"> Application Settings</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive ps">
                        <table class="table tablesorter " id="">
                            <thead class=" text-primary">
                            <tr>
                                <th>
                                    #
                                </th>
                                <th>
                                    Key
                                </th>

                                <th>
                                    Value
                                </th>
                                <th class="text-center">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody id="settings_table">
                            @foreach($settings as $setting)
                            <tr data-id="{{$setting->id}}">
                                <td>
                                    {{$setting->id}}
                                </td>
                                <td class="settings_name">
                                    {{$setting->settings_key}}
                                </td>

                                <td>
                                    {{$setting->settings_value}}
                                </td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" class="btn btn-warning edit_settings" data-id="{{$setting->id}}" data-settings_key="{{$setting->settings_key}}" data-settings_value="{{$setting->settings_value}}">Edit</a>

                                    <form action="/admin/app-settings-destroy/{{$setting->id}}" class="fc_delete" method="post" style="display:inline;" id="delete_form_{{$setting->id}}">
                                        @csrf
                                        <button  class="btn btn-danger delete_settings_btn" data-toggle="modal" data-target="#confirmationModal" >Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card  card-plain">
                <div class="card-header">
                    <h4 class="card-title"> Add a New Key Value </h4>
                </div>
                <div class="card-body">
                    <div id="backend_settings_errors_create"></div>
                    <form method="post" id="store_settings" action="/admin/app-settings-store">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Key</label>
                                    <input type="text" name="settings_key" class="form-control fc" id="key_store" placeholder="key" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Value</label>
                                    <input type="text" name="settings_value" class="form-control fc" id="value_store" placeholder="value" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group d-flex">
                                    <button type="submit" class="btn btn-primary" id="settings_store_btn">Create</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr/>
            <div class="card  card-plain d-none" id="edit_div">
                <div class="card-header">
                    <h4 class="card-title"> Edit Settings</h4>
                </div>
                <div class="card-body">
                <div id="backend_settings_errors_edit"></div>
                    <form method="post" id="settings_update_form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Key</label>
                                    <input type="text" name="settings_key" class="form-control fc" id="settings_key_update" placeholder="key" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Value</label>
                                    <input type="text" name="settings_value" class="form-control fc" id="settings_value_update" placeholder="value" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group d-flex" style="">
                                    <button type="submit" class="btn btn-primary" id="settings_update_btn">Update</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>

@endsection
@section('scripts')
    <script>

const rules={
           settings_key: {minLength: 2, maxLength:30, required:true},
           settings_value: {maxLength:250, required:true},
        }
        const createTableRow=(data)=>{
            let row=`
            <tr data-id="${data.id}">
                <td>
                    ${data.id}
                </td>
                <td class="settings_key">
                    ${data.settings_key}
                </td>

                <td class="settings_value">
                    ${data.settings_value}
                </td>
                <td class="text-center">
                    <a href="javascript:void(0)" class="btn btn-warning edit_settings" data-id="${data.id}"data-key="${data.settings_key}" "data-value="${data.settings_value}">Edit</a>

                    <form action="/admin/app-settings-destroy/${data.id}" class="fc_delete" method="post" style="display:inline;" id="delete_form_${data.id}">
                        @csrf
                    <button  class="btn btn-danger delete_settings_btn" data-toggle="modal" data-target="#confirmationModal" >Delete</button>
                    </form>
                </td>
            </tr>
            `;
            document.getElementById('settings_table').innerHTML +=row;
        }


 
        const resetForms =()=>{
            let forms=document.getElementsByTagName('form');
            for(let i=0; i<forms.length; i++){
                forms[i].reset();
            }
        }
        

        const clearErrorMessages=(id)=>{
            let errorDiv=document.getElementById(id);
            errorDiv.innerHTML="";
            
        }

        const submitWithAjax=(data)=>{
            if(data.data.success){
                createTableRow(data.data.settings);
                //resetForms();
                clearErrorMessages("backend_settings_errors_create");
                //clear inputs
                resetForms();
                addEditAndDeleteListeners();

            }else{
                //we have some errors
                //console.log(data.data.settings)
                putErrorsInSingleDiv('backend_settings_errors_create', data.data.settings, ['alert', 'alert-danger'], 'p')

            }
        }

        const submitCreateForm =()=>{
            let create_form=document.getElementById('store_settings');
            create_form.addEventListener('submit', (e)=>{
                e.preventDefault();
                let formCheck=new FormSubmition('store_settings', rules, 'ajax', submitWithAjax);
                if(formCheck.hasErrors()){
                    formCheck.putErrorsAboveEveryInput(formCheck.errorsObj, ['alert', 'alert-danger'], 'p');
                }else{
                    formCheck.sendPostViaAjax();
                    
                }
                

            });
        }

        const submitDeleteForm =(e)=>{
            let form=e.target.closest('form');
            let form_id=form.getAttribute('id');
            //console.log(form)
            document.getElementById('submit_modal').addEventListener('click', function (e) {
                e.preventDefault();
                let formCheck=new FormSubmition(form_id, {}, 'ajax', deleteAfterAjax);
                formCheck.sendPostViaAjax();
            });

            
        }

        const deleteAfterAjax=(data)=>{
            if(data.data[0]=="success"){
               //remove row
                let delete_form=document.getElementById("delete_form_"+data.data[1]);
                let row=delete_form.closest('tr');
                row.remove();
                //resetForms();
                clearErrorMessages('backend_delete_errors');
                document.getElementById('confirmationModal').classList.remove('show');
                //addEditAndDeleteListeners();
            }else{
                //we have some errors
                putErrorsInSingleDiv('backend_delete_errors', data.data.settings, ['alert', 'alert-danger'], 'p')

            }
        }

        const editRow=(e)=>{
            //show form and fill with data
            let edit_div=document.getElementById('edit_div');
            edit_div.classList.remove('d-none');
            let edit_form=document.getElementById('settings_update_form');
            let old_key_input=document.getElementById('settings_key_update');
            let old_value_input=document.getElementById('settings_value_update');

            old_key_input.value=e.target.getAttribute('data-settings_key');
            old_value_input.value=e.target.getAttribute('data-settings_value');

            edit_form.setAttribute('action', "/admin/app-settings-update/"+e.target.getAttribute('data-id'));

            let update_form=document.getElementById('settings_update_form');
            update_form.addEventListener('submit', (e)=>{
                e.preventDefault();

                let formCheck=new FormSubmition('settings_update_form', rules, 'http');
                if(formCheck.hasErrors()){
                    formCheck.putErrorsAboveEveryInput(formCheck.errorsObj, ['alert', 'alert-danger'], 'p');
                }else{
                    //formCheck.sendPostViaAjax();
                    //edit_div.classList.add('d-none');
                    edit_form.submit();
                    //location.reload();
                }
                

            });

        }



        function addEditAndDeleteListeners(){
            let edits=document.getElementsByClassName('edit_settings');
            for(let i=0; i<edits.length; i++){
                edits[i].addEventListener('click',(e)=> {
                    editRow(e);
                })
            }
            let delete_forms=document.getElementsByClassName('fc_delete');
            for(let i=0; i<delete_forms.length; i++){
                delete_forms[i].addEventListener('submit',(e)=> {
                    e.preventDefault();
                    submitDeleteForm(e)
                })
            }
        }

       addEditAndDeleteListeners();

       submitCreateForm();

    </script>
    @endsection

