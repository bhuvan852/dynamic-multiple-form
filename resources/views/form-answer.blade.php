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
         <div class="card">
            <div class="card-header">
               <h4>Forms Answer Management</h4>
            </div>
            <div class="card-body">
            <div class="form-row">
                     <div class="col-md-5">
                        <div class="form-group">
                           <label for="form_name">Form Name</label>
                           <select class="form-control"  id="form_id">
                              <option value="">---Select---</option>
                              @foreach($forms as $form)
                                 <option value="{{$form->id}}">{{$form->form_name}} </option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-5">
                        <div class="form-group">
                           <label for="form_name">User</label>
                               <select class="form-control"  id="user_id">
                              <option value="">---Select---</option>
                              @foreach($users as $user)
                                 <option value="{{$user->id}}">{{$user->name}} ({{$user->email}})</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                      <div class="col-md-2">
                        <div class="form-group">
                           <a class='btn btn-xs btn-success' style="width: 100%;margin-top: 32px;" onClick="fetchData()">Filter</a>
                        </div>
                     </div>
                     </div>
               <table id="dataTable" class="table table-striped">
                  <thead>
                     <tr>
                        <th>S.N.</th>
                        <th>Form Name</th>
                        <th>User Full Name</th>
                        <th>User Email</th>
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
            url: "{{ route('form-answer.get-data') }}", 
            type: 'GET',
            data: {
               form_id:$("#form_id").val(),
               user_id:$("#user_id").val(),
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
    </script>
   </body>
</html>