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
                    <h4 class="card-title"> Categories</h4>
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
                                    Color
                                </th>
                                <th>
                                    Topics
                                </th>
                                <th class="text-center">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody id="category_table">
                            @foreach($categories as $category)
                            <tr data-id="{{$category->id}}">
                                <td>
                                    {{$category->id}}
                                </td>
                                <td class="category_name">
                                    {{$category->name}}
                                </td>
                                <td>
                                    <div class="color-table" style="background-color: {{$category->color}}"></div>
                                </td>
                                <td>
                                    {{$category->topics_count}}
                                </td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" class="btn btn-warning edit_category" data-id="{{$category->id}}" data-name="{{$category->name}}" data-color="{{$category->color}}">Edit</a>

                                    <form action="/admin/category-destroy/{{$category->id}}" class="fc_delete" method="post" style="display:inline;" id="delete_form_{{$category->id}}">
                                        @csrf
                                        <button  class="btn btn-danger delete_category_btn" data-toggle="modal" data-target="#confirmationModal" >Delete</button>
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
                    <h4 class="card-title"> Add a New Category</h4>
                </div>
                <div class="card-body">
                    <div id="backend_category_errors_create"></div>
                    <form method="post" id="store_category" action="/admin/categories-store">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" class="form-control fc" id="category_name_store" placeholder="Category" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" style="">
                                    <label for="color">Color</label>
                                    <input type="color" name="color" class="form-control fc" id="category_color_store" style="height: 5rem; width: 20%;">
                                </div>
                                <div class="form-group d-flex" style="">
                                    <button type="submit" class="btn btn-primary" id="category_store_btn">Create</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr/>
            <div class="card  card-plain d-none" id="edit_div">
                <div class="card-header">
                    <h4 class="card-title"> Edit Category</h4>
                </div>
                <div class="card-body">
                <div id="backend_category_errors_edit"></div>
                    <form method="post" id="category_update_form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" class="form-control fc" id="category_name_update" placeholder="Category" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" style="">
                                    <label for="color">Color</label>
                                    <input type="color" name="color" class="form-control fc" id="category_color_update" style="height: 5rem; width: 20%;">
                                </div>
                                <div class="form-group d-flex" style="">
                                    <button type="submit" class="btn btn-primary" id="category_update_btn">Update</button>
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
            'color':{color:true,required:true}
        }
        const createTableRow=(data)=>{
            let row=`
            <tr data-id="${data.id}">
                <td>
                    ${data.id}
                </td>
                <td class="category_name">
                    ${data.name}
                </td>
                <td>
                    <div class="color-table" style="background-color: ${data.color}"></div>
                </td>
                <td>
                    0
                </td>
                <td class="text-center">
                    <a href="javascript:void(0)" class="btn btn-warning edit_category" data-id="${data.id}" data-name="${data.name}" data-color="${data.color}">Edit</a>

                    <form action="/admin/category-destroy/${data.id}" class="fc_delete" method="post" style="display:inline;" id="delete_form_${data.id}">
                        @csrf
                    <button  class="btn btn-danger delete_category_btn" data-toggle="modal" data-target="#confirmationModal" >Delete</button>
                    </form>
                </td>
            </tr>
            `;
            document.getElementById('category_table').innerHTML +=row;
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
                createTableRow(data.data.category);
                //resetForms();
                clearErrorMessages("backend_category_errors_create");
                //clear inputs
                resetForms();
                addEditAndDeleteListeners();

            }else{
                //we have some errors
                //console.log(data.data.category)
                putErrorsInSingleDiv('backend_category_errors_create', data.data.category, ['alert', 'alert-danger'], 'p')

            }
        }

        const submitCreateForm =()=>{
            let create_form=document.getElementById('store_category');
            create_form.addEventListener('submit', (e)=>{
                e.preventDefault();
                let formCheck=new FormSubmition('store_category', rules, 'ajax', submitWithAjax);
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
                putErrorsInSingleDiv('backend_delete_errors', data.data.category, ['alert', 'alert-danger'], 'p')

            }
        }

        const editRow=(e)=>{
            //show form and fill with data
            let edit_div=document.getElementById('edit_div');
            edit_div.classList.remove('d-none');
            let edit_form=document.getElementById('category_update_form');
            let old_name_input=document.getElementById('category_name_update');
            let old_color_input=document.getElementById('category_color_update');
            old_name_input.value=e.target.getAttribute('data-name');
            old_color_input.value=e.target.getAttribute('data-color');
            edit_form.setAttribute('action', "/admin/category-update/"+e.target.getAttribute('data-id'));

            let update_form=document.getElementById('category_update_form');
            update_form.addEventListener('submit', (e)=>{
                e.preventDefault();

                let formCheck=new FormSubmition('category_update_form', rules, 'http');
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
            let edits=document.getElementsByClassName('edit_category');
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

