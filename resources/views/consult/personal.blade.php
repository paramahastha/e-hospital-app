@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-10">
         <div class="card">
            <div class="card-header">
               <div class="row">
                  <div class="col-md-6 mt-3">
                     <div class="d-flex align-items-center">
                     {{ __('Lengkapi Data Diri') }}
                     </div>
                  </div>
                  <div class="col-md-6 mt-2 mb-2">
                     <a href="{{ URL::previous() }}" class="btn btn-secondary float-end">
                        Back
                     </a>
                  </div>  
               </div>             
            </div>

            <div class="card-body">
                  @if (session('status'))
                     <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                     </div>
                  @endif
                  <br>        
                  <div class="col-md-12">   
                     <form method="POST" action="{{route('consult.complete', ['user' => $user->id, 'consult' => $consult->id])}}">
                         @csrf                                                                       
                         <div class="col-md-12"> 
                             <div class="row">
                                 <div class="col-md-12">
                                    <div class="card">
                                       <div class="card-body">                                          
                                          <div class="col-md-6">
                                             <div class="form-group">
                                                <label>Name</label>                                             
                                                <input type="text" class="form-control" 
                                                   name="name" disabled value="{{$currUser->name}}">
                                             </div>
                                             <div class="form-group">
                                                <label>Email</label>                                             
                                                <input type="text" class="form-control" 
                                                   name="email" disabled value="{{$currUser->email}}">
                                             </div>
                                             <div class="form-group">
                                                <label>Date of Birth <strong style="color:red;">*</strong></label>
                                                <div class='input-group date' id='datetimepicker'>
                                                   <input type='text' name='dob' 
                                                      value="{{!is_null($currUser->userInfo) ? $currUser->userInfo->date_of_birth : ''}}"
                                                      class="form-control" required placeholder="Pilih Tanggal"/>
                                                   <span class="input-group-addon">
                                                      <span class="glyphicon glyphicon-calendar"></span>
                                                   </span>
                                                </div>
                                             </div>                                             
                                             <div class="form-group">
                                                <label for="gender">Gender <strong style="color:red;">*</strong></label> <br>                                                
                                                <label for="gender">
                                                   <input type="radio" name="gender" {{(!is_null($currUser->userInfo) && $currUser->userInfo->gender) == 'Male' ? 'checked' : 'checked'}} value="Male"> Male
                                                   <input type="radio" name="gender" {{(!is_null($currUser->userInfo) && $currUser->userInfo->gender) == 'Female' ? 'checked' : ''}} value="Female"> Female                                                   
                                                </label>                                                
                                             </div>                                                                                        
                                          </div>   
                                          <div class="col-md-6">
                                             <div class="form-group">
                                                <label>Identity Number <strong style="color:red;">*</strong></label>                                             
                                                <input type="number" class="form-control" 
                                                   name="identity_number" value={{!is_null($currUser->userInfo) ? $currUser->userInfo->identity_number : ''}} 
                                                   required placeholder="No KTP/Passport">
                                             </div>  
                                             <div class="form-group">
                                                <label>Phone Number <strong style="color:red;">*</strong></label>                                             
                                                <input type="number" class="form-control" 
                                                   name="phone_number" value={{!is_null($currUser->userInfo) ? $currUser->userInfo->phone_number : ''}} 
                                                   required placeholder="Nomor Telepon">
                                             </div>
                                       
                                             <div class="form-group">
                                                <label>Address</label>                                             
                                                <textarea rows="4" class="form-control"                                                    
                                                   name="address" placeholder="Alamat">{{!is_null($currUser->userInfo) ? $currUser->userInfo->address : ''}}</textarea>
                                             </div>
                                          </div>
                                       </div>                                                                          
                                   </div>  
                                 </div>
                                 <div class="col-md-12 mt-4 mb-2">
                                    <button type="submit" class="btn btn-primary float-end">
                                       Make Appointment
                                    </button>    
                                 </div>                                                                                             
                             </div>                                                                            
                         </div>                                                                                                        
                     </form>   
                 </div>                                                      
            </div>
         </div>
      </div>
   </div> 
</div>

@endsection