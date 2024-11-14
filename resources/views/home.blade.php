<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Admin Dashboard</title>
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
      <style>
         body {
         padding-top: 20px;
         }
        .form-footer {
            border-top: 2px solid #ccc; 
            padding: 10px;
            margin-top: 20px;
            text-align: center;
            background-color: #f8f9fa; 
        }
      </style>
   </head>
   <body>
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
         <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
               <ul class="navbar-nav ml-auto">
                  <li class="nav-item">
                        <a class="nav-link active" href="{{route('home')}}">Dynamic Form Management</a>
                     </li>
                  <li class="nav-item">
                     <a class="nav-link active" href="{{route('form-answer')}}">Form Answer Management</a>
                  </li>
                     <li class="nav-item">
                   <a class="nav-link" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                    </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                  </li>
               </ul>
            </div>
         </div>
      </nav>
      <div class="container">
         <div class="card mb-4">
            <div class="card-header">
               <h4>Dynamic Form Management</h4>
            </div>
            <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" onclick="this.closest('.alert').style.display='none';">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" onclick="this.closest('.alert').style.display='none';">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>
            @elseif($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert" onclick="this.closest('.alert').style.display='none';">
                <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>
            @endif
             <form action="{{isset($dynamicForm) ?  route('dynamic-form.update')  :  route('dynamic-form.store') }}" method="post" enctype="multipart/form-data">
              @csrf
              @if(isset($dynamicForm))
               <input type="hidden" name="dynamic_form_id" value={{$dynamicForm->id}}>
              @endif
                  <div class="form-row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label for="form_name">Form Name</label>
                           <input type="text" class="form-control" name="form_name" placeholder="Enter form name" value="{{ isset($dynamicForm) ? $dynamicForm->form_name : '' }}" required>
                        </div>
                     </div>
                     <div class="col-md-12">
                        <h3>Form Attributes:</h3>
                     </div>
                     <div id="dynamic_form_container">
                        @if(isset($dynamicForm))
                        @foreach($dynamicForm->fields as $dynamic_field)
                        <div class="row dynamic_form_items">
                           <div class="col-md-5">
                              <div class="form-group">
                                 <label for="label_name">Label</label>
                                 <input type="text" class="form-control" name="label_name[]" value="{{$dynamic_field->label_name}}" required>
                              </div>
                           </div>
                           <div class="col-md-5">
                              <div class="form-group">
                                 <label>Is required:</label>
                                <select class="form-control"  name="is_required[]" required>
                                <option value="yes" @if($dynamic_field->is_required == "yes") selected @endif>Yes</option>
                                    <option value="no" @if($dynamic_field->is_required == "no") selected @endif>No</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-5">
                              <div class="form-group">
                                 <label for="field_type">Field Type</label>
                                 <select class="form-control"  name="field_type[]" required>
                                    <option value="">---Select---</option>
                                    <option value="text" @if($dynamic_field->field_type == "text") selected @endif>Text</option>
                                    <option value="textarea" @if($dynamic_field->field_type == "textarea") selected @endif>Textarea</option>
                                    <option value="radio" @if($dynamic_field->field_type == "radio") selected @endif>Radio</option>
                                    <option value="checkbox" @if($dynamic_field->field_type == "checkbox") selected @endif>Checkbox</option>
                                    <option value="dropdown" @if($dynamic_field->field_type == "dropdown") selected @endif>Dropdown</option>
                                    <option value="file" @if($dynamic_field->field_type == "file") selected @endif>File</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-5 field_option_div">
                              <div class="form-group">
                                 <label for="field_option">Field Options</label>
                                 <input type="text" class="form-control field_option" name="field_option[]"  placeholder="for radio, checkbox, and dropdown" value="{{$dynamic_field->field_option}}">
                                 <p style="color:red;font-size:12px;">Note: Use "," for multiple options (Example: option1,option2,option3,....) </p>
                              </div>
                           </div>
                           <div class="col-md-2 remove_btn_div">
                            <a style="margin:10px;"  class="btn btn-danger mt-3 remove_field">Remove X </a>
                            </div>
                        </div>
                        @endforeach

                        @else

                        <div class="row dynamic_form_items">
                           <div class="col-md-5">
                              <div class="form-group">
                                 <label for="label_name">Label</label>
                                 <input type="text" class="form-control" name="label_name[]" required>
                              </div>
                           </div>
                           <div class="col-md-5">
                              <div class="form-group">
                                 <label>Is required:</label>
                                <select class="form-control"  name="is_required[]" required>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-5">
                              <div class="form-group">
                                 <label for="field_type">Field Type</label>
                                 <select class="form-control"  name="field_type[]" required>
                                    <option value="">---Select---</option>
                                    <option value="text">Text</option>
                                    <option value="textarea">Textarea</option>
                                    <option value="radio">Radio</option>
                                    <option value="checkbox">Checkbox</option>
                                    <option value="dropdown">Dropdown</option>
                                    <option value="file">File</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-5 field_option_div">
                              <div class="form-group">
                                 <label for="field_option">Field Options</label>
                                 <input type="text" class="form-control field_option" name="field_option[]"  placeholder="for radio, checkbox, and dropdown">
                                 <p style="color:red;font-size:12px;">Note: Use "," for multiple options (Example: option1,option2,option3,....) </p>
                              </div>
                           </div>
                           <div class="col-md-2 remove_btn_div" style="display:none">
                            <a style="margin:10px;"  class="btn btn-danger mt-3 remove_field">Remove X </a>
                            </div>
                        </div>

                        @endif
                     </div>
                  </div>
                  <a class="btn btn-primary add_more" id="add_more">+Add more </a>

                <div class="form-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>

               </form>
            </div>
         </div>
         <div class="card">
            <div class="card-header">
               <h4>Dynamic Forms</h4>
            </div>
            <div class="card-body">
               <table id="dataTable" class="table table-striped">
                  <thead>
                     <tr>
                        <th>S.N.</th>
                        <th>Form Name</th>
                        <th>Shareable form link</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody id="tableBody">
                  </tbody>
               </table>
            </div>
         </div>
      </div>
      <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
      <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
      <script>
        $(document).ready(function() {
            fetchData();
        });

    function fetchData() {
        $.ajax({
            url: "{{ route('dynamic-form.get-data') }}", 
            type: 'GET',
            data: {
                
            },
            success: function(response) {
                $('#tableBody').empty().append(response.html);
                  $('#dataTable').DataTable();
            },
            error: function() {
                alert('An error occurred while fetching data.');
            }
        });
    }

        $('#add_more').click(function() {
            var newField = $('.dynamic_form_items:first').clone();
            newField.find('input[type="text"]').val('');
            newField.find('input[type="radio"]').prop('checked', false);
            newField.find('select').prop('selectedIndex', 0);
            newField.find(".remove_btn_div").show();
            $('#dynamic_form_container').append(newField);
        });
        $('#dynamic_form_container').on('click', '.remove_field', function() {
            $(this).closest('.dynamic_form_items').remove();
        });
      </script>
   </body>
</html>