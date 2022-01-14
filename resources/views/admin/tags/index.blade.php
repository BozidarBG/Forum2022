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
                    <h4 class="card-title"> Tags</h4>
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
                                    Name
                                </th>

                                <th>
                                    Topics
                                </th>
                                <th class="text-center">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody id="tag_table">
                            @foreach($tags as $tag)
                            <tr data-id="{{$tag->id}}">
                                <td>
                                    {{$tag->id}}
                                </td>
                                <td class="tag_name">
                                    {{$tag->name}}
                                </td>

                                <td>
                                    {{$tag->topics_count}}
                                </td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" class="btn btn-warning edit_tag" data-id="{{$tag->id}}" data-name="{{$tag->name}}" >Edit</a>

                                    <form action="/admin/tag-destroy/{{$tag->id}}" class="fc_delete" method="post" style="display:inline;" id="delete_form_{{$tag->id}}">
                                        @csrf
                                        <button  class="btn btn-danger delete_tag_btn" data-toggle="modal" data-target="#confirmationModal" >Delete</button>
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
                    <h4 class="card-title"> Add a New Tag</h4>
                </div>
                <div class="card-body">
                    <div id="backend_tag_errors_create"></div>
                    <form method="post" id="store_tag" action="/admin/tags-store">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" class="form-control fc" id="tag_name_store" placeholder="tag" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group d-flex" style="">
                                    <button type="submit" class="btn btn-primary" id="tag_store_btn">Create</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr/>
            <div class="card  card-plain d-none" id="edit_div">
                <div class="card-header">
                    <h4 class="card-title"> Edit Tag</h4>
                </div>
                <div class="card-body">
                <div id="backend_tag_errors_edit"></div>
                    <form method="post" id="tag_update_form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" class="form-control fc" id="tag_name_update" placeholder="tag" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group d-flex" style="">
                                    <button type="submit" class="btn btn-primary" id="tag_update_btn">Update</button>
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
           'name': {minLength: 2, maxLength:20, required:true},
        }
        const createTableRow=(data)=>{
            let row=`
            <tr data-id="${data.id}">
                <td>
                    ${data.id}
                </td>
                <td class="tag_name">
                    ${data.name}
                </td>

                <td>
                    0
                </td>
                <td class="text-center">
                    <a href="javascript:void(0)" class="btn btn-warning edit_tag" data-id="${data.id}" data-name="${data.name}">Edit</a>

                    <form action="/admin/tag-destroy/${data.id}" class="fc_delete" method="post" style="display:inline;" id="delete_form_${data.id}">
                        @csrf
                    <button  class="btn btn-danger delete_tag_btn" data-toggle="modal" data-target="#confirmationModal" >Delete</button>
                    </form>
                </td>
            </tr>
            `;
            document.getElementById('tag_table').innerHTML +=row;
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
                createTableRow(data.data.tag);
                //resetForms();
                clearErrorMessages("backend_tag_errors_create");
                //clear inputs
                resetForms();
                addEditAndDeleteListeners();

            }else{
                //we have some errors
                //console.log(data.data.tag)
                putErrorsInSingleDiv('backend_tag_errors_create', data.data.tag, ['alert', 'alert-danger'], 'p')

            }
        }

        const submitCreateForm =()=>{
            let create_form=document.getElementById('store_tag');
            create_form.addEventListener('submit', (e)=>{
                e.preventDefault();
                let formCheck=new FormSubmition('store_tag', rules, 'ajax', submitWithAjax);
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
                putErrorsInSingleDiv('backend_delete_errors', data.data.tag, ['alert', 'alert-danger'], 'p')

            }
        }

        const editRow=(e)=>{
            //show form and fill with data
            let edit_div=document.getElementById('edit_div');
            edit_div.classList.remove('d-none');
            let edit_form=document.getElementById('tag_update_form');
            let old_name_input=document.getElementById('tag_name_update');
            old_name_input.value=e.target.getAttribute('data-name');
            edit_form.setAttribute('action', "/admin/tag-update/"+e.target.getAttribute('data-id'));

            let update_form=document.getElementById('tag_update_form');
            update_form.addEventListener('submit', (e)=>{
                e.preventDefault();

                let formCheck=new FormSubmition('tag_update_form', rules, 'http');
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
            let edits=document.getElementsByClassName('edit_tag');
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

