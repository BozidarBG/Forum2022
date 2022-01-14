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
                                    Threads
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
                                    56
                                </td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" class="btn btn-warning edit_category" data-id="{{$category->id}}" data-name="{{$category->name}}" data-color="{{$category->color}}">Edit</a>

                                    <form action="/admin-category-delete/{{$category->id}}" class="fc_delete" method="post" style="display:inline;">
                                        @csrf
                                    <button  class="btn btn-danger delete_category_btn" data-toggle="modal" data-target="#confirmationModal" >Delete</button>
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
                    <div id="backend_category_errors"></div>
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

                            <div id="backend_errors"></div>
                        </div>

                        </div>
                    </form>
                </div>
            </div>
            <hr/>
            <div class="card  card-plain d-none" id="edit_form">
                <div class="card-header">
                    <h4 class="card-title"> Edit Category</h4>
                </div>
                <div class="card-body">
                    <form method="post" id="category_update">
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

                                <div id="backend_errors"></div>
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
                    56
                </td>
                <td class="text-center">
                    <a href="javascript:void(0)" class="btn btn-warning edit_category" data-id="${data.id}" data-name="${data.name}" data-color="${data.color}">Edit</a>

                    <form action="/admin-category-delete/${data.id}" class="fc_delete" method="post" style="display:inline;">
                        @csrf
                    <button  class="btn btn-danger delete_category_btn" data-toggle="modal" data-target="#confirmationModal" >Delete</button>
                </td>
            </tr>
            `;
            document.getElementById('category_table').innerHTML +=row;
        }
        /*
        const resetForms =()=>{
            let forms=document.getElementsByTagName('form');
            for(let i=0; i<forms.length; i++){
                forms[i].reset();
            }
        }
        */

        const clearErrorMessages=()=>{
            let errorDiv=document.getElementById('backend_category_errors');
            errorDiv.innerHTML="";
            
        }

        const submitWithAjax=(data)=>{
            //console.log(data.data)
            if(data.data.success){
               // console.log(data.data.category) //ok {name: 'bbbbb', color: '#000000', slug: 'bbbbb', id: 40}
                createTableRow(data.data.category);
                //resetForms();
                clearErrorMessages();
            }else{
                //we have some errors
                //console.log(data.data.category)
                putErrorsInSingleDiv('backend_category_errors', data.data.category, ['alert', 'alert-danger'], 'p')

            }
        }

        const submitCreateForm =()=>{
            let create_form=document.getElementById('store_category');
            console.log(create_form)
            
            create_form.addEventListener('submit', (e)=>{
                e.preventDefault();
                let formCheck=new FormSubmition('category_store', rules, 'ajax', submitWithAjax);
                //console.log(formCheck)
                if(formCheck.hasErrors()){
                    //show errors
                    //console.log(formCheck);
                    formCheck.putErrorsAboveEveryInput(formCheck.errorsObj, ['alert', 'alert-danger'], 'p');
                }else{
                    //there are no errors so we can submit
                    //form.submit();
                    //formCheck.sendPostViaAjax();
                    //console.log(formCheck.success)
                    formCheck.sendPostViaAjax();
                    
                }
                

            });
        }
/*
        const submitDeleteForm =(e)=>{
            let form=e.target.closest('form');
            console.log(form)
            return;
            form.addEventListener('submit', (e)=>{
                e.preventDefault();
                //let formCheck=new FormSubmition('d', rules, 'ajax', submitWithAjax);
                //console.log(formCheck)
                if(formCheck.hasErrors()){
                    //show errors
                    //console.log(formCheck);
                    formCheck.putErrorsAboveEveryInput(formCheck.errorsObj, ['alert', 'alert-danger'], 'p');
                }else{
                    //there are no errors so we can submit
                    //form.submit();
                    //formCheck.sendPostViaAjax();
                    //console.log(formCheck.success)
                    formCheck.sendPostViaAjax();
                    
                }
                

            });
        }
*/

        function addEditAndDeleteListeners(){
            let edits=document.getElementsByClassName('edit_category');
            for(let i=0; i<edits.length; i++){
                edits[i].addEventListener('click',function () {
                    edit(this);
                })
            }
            let delete_forms=document.getElementsByClassName('fc_delete');
            for(let i=0; i<delete_forms.length; i++){
                delete_forms[i].addEventListener('submit',(e)=> {
                    e.preventDefault();
                    submtitDeleteForm(e)
                })
            }
        }

       addEditAndDeleteListeners();

       submitCreateForm();
      
/* sve dole je sranje */
/*
       

        function edit(t){
            let changeEdited=function (data) {
                //alert(t.closest('tr'))
                //c(old_id)
                let tr=t.closest('tr');
                let new_name=document.getElementById('category_name_update').value;
                let new_color=document.getElementById('category_color_update').value;
                tr.setAttribute('data-name', new_name);
                tr.setAttribute('data-color', new_color);
                tr.getElementsByClassName('category_name')[0].innerHTML=new_name;
                let t_color=
                tr.getElementsByClassName('color-table')[0].style.backgroundColor=new_color;
                document.getElementById('edit_form').classList.add('d-none');
            }


            let old_name=t.closest('[data-name]').getAttribute('data-name');
            let old_color=t.closest('[data-color]').getAttribute('data-color');
            let old_id=t.closest('[data-id]').getAttribute('data-id');
            document.getElementById('edit_form').classList.remove('d-none');
            document.getElementById('category_color_update').value=old_color;
            document.getElementById('category_name_update').value=old_name;
            document.getElementById('category_update_btn').addEventListener('click', function (e) {
                e.preventDefault();
                const rules={
                    'name': {minLength: 2, maxLength:50},
                };

                const route="{{config('app.url')}}"+"/admin/categories-update/"+old_id;
                new FormSubmition('category_update', rules, route, 'backend_errors', changeEdited, 'post');
            });

        }

        function deleteCategory(t) {
            //when pressed, deleteConfirmationModal is activated.
            //when pressed Yes (id=confirm_delete) we send row id via ajax, wait for success confirmation and then remove row
            let rowToBeDeleted=t.closest('[data-id]');
            let id=rowToBeDeleted.getAttribute('data-id');
            document.getElementById('category_name').innerText=rowToBeDeleted.getAttribute('data-name');
            document.getElementById('confirm_delete').addEventListener('click', function () {
                const rules={};
                const route="{{config('app.url')}}"+"/admin/categories-destroy/"+id;

                new FormSubmition(null, rules, route, 'backend_errors', removeRow, "delete");
            });

            function removeRow(id_from_backend){
                if(id==id_from_backend){
                    rowToBeDeleted.classList.add('bg-danger');
                    setTimeout(function () {
                        rowToBeDeleted.remove()
                    }, 1500);

                    //close modal
                    document.getElementById('confirmationModal').classList.toggle('show');

                }else{
                    //some error maybe so just refresh
                    document.reload();
                }

            }
        }

*/



    </script>
    @endsection

