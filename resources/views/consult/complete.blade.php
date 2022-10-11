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
                     {{ __('') }}
                     </div>
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
                    <div class="alert alert-success" role="alert">
                        <h4 class="text-center">We will process your consultation appointment soon and will let you know, thank you!</h4>
                    </div>
                 </div>   
                 
            </div>
            <div class="card-footer">
                 <div class="text-center mt-2 mb-2">
                    <a class="btn btn-primary" href="{{ route('home') }}">
                        {{ __('Done') }}
                    </a>                    
                </div>
            </div>
         </div>
      </div>
   </div> 
</div>

@endsection