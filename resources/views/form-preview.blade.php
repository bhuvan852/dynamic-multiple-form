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
            <a class="navbar-brand" href="#">MDFMS</a>
         </div>
      </nav>
      <div class="container">
         <div class="card mb-4">
            <div class="card-header">
               <h4>{{$form->form_name}}</h4>
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
             <form action="{{route('user-ui.dynamic.store') }}" method="post" enctype="multipart/form-data">
              @csrf 
              <input type="hidden" name="form_slug" value={{$form->slug}}>
                  <div class="form-row">
                    <div class="col-md-12" >
                        <h6>User Detail:</h6>
                     </div>
                     <div class="row col-md-12">
                     <div class="col-md-6">
                           <div class="form-group">
                              <label for="full_name">Full Name</label>
                              <input type="text" class="form-control" name="full_name" placeholder="" required value="{{isset($dUser) ? $dUser->name : '' }}">
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="user_email">Email</label>
                              <input type="email" class="form-control" name="user_email" placeholder="" required value="{{isset($dUser) ? $dUser->email : ''}}">
                           </div>
                        </div>
                     </div>
                     @foreach($formFieldsHtmls as $formFieldsHtml)
                           <div class="col-md-6">
                              <div class="form-group">
                                {!! $formFieldsHtml  !!}
                              </div>
                           </div>
                           @endforeach
                     </div>
               @if(!isset($detailPage))
                <div class="form-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
               @endif
               </form>
            </div>
         </div>
      </div>
   </body>
</html>